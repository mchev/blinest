# Proposition : Refonte affichage joueurs et calcul des points

## Contexte

- **Bug d’invisibilité** : un joueur qui rejoint en cours de partie n’apparaît dans le classement des autres que lorsqu’il marque son premier point.
- **Cause identifiée** : la liste des joueurs affichée dépend uniquement du **canal de présence** (Echo `.here()` / `.joining()` / `.leaving()`). Si l’événement `.joining()` n’est pas reçu ou est retardé par un client déjà dans la room, ce client ne met jamais à jour sa liste et le nouveau joueur reste invisible. Il n’apparaît qu’au premier `NewScore`, car à ce moment-là il est souvent déjà présent dans `users` (joining arrivé entre-temps) ou on tente de l’ajouter à partir de l’événement (avec les limites actuelles).
- **Problèmes connexes** : double source de vérité (présence + Redis), logique de sync complexe dans le front (plusieurs watchers, cas particuliers), risque de classement incohérent ou de scores “en retard”.

L’objectif est un système **fiable**, **performant** et **peu gourmand** côté serveur.

---

## Principes directeurs

1. **Une seule source de vérité côté serveur** pour “qui est dans la room” et “quel est le score de qui”.
2. **Le client affiche ce que le serveur envoie** : pas de reconstruction fragile à partir de plusieurs canaux (présence + events + Redis).
3. **Réduction des race conditions** : moins de logique “si X alors merge avec Y” dans le front.
4. **Coût serveur maîtrisé** : pas de polling agressif, réutilisation de Redis et des events existants.

---

## Option A : Source de vérité “room state” (recommandée)

### Idée

Le serveur expose un **état de room** minimal et fiable :

- **Qui est dans la room** : dérivé côté serveur (présence ou session) et renvoyé dans un payload unique.
- **Scores du round en cours** : déjà en Redis ; on les attache à ce même payload quand un round est actif.

Le client ne se base plus sur la seule présence Echo pour la liste des joueurs : il reçoit une **liste explicite** (via un event ou un endpoint) qu’il affiche telle quelle.

### Mécanismes possibles

1. **Event dédié “RoomState” (ou “PlayersAndScores”)**
   - Déclenché :
     - à chaque **join** et **leave** du canal de la room (écouter les events de présence côté backend et émettre un event custom),
     - optionnellement à intervalle très espacé en heartbeat (ex. toutes les 30–60 s) pour auto-réparation.
   - Payload : `{ users: [...], scores: { [userId]: number }, roundId }`.
   - Les clients écoutent cet event et remplacent leur liste joueurs + scores par ce payload (ou font un merge simple et déterministe).
   - **Avantage** : tous les clients reçoivent la même vérité au même moment ; plus de dépendance au bon fonctionnement de `.joining()` côté client.
   - **Coût** : un broadcast par join/leave (déjà un join/leave par joueur) + éventuellement un heartbeat léger.

2. **Endpoint “état de la room” + sync périodique légère**
   - `GET /rooms/{id}/state` (ou intégré à un endpoint existant type `joined`) qui retourne :
     - la liste des utilisateurs actuellement dans la room (via Reverb/Pusher presence API ou un cache serveur),
     - les scores du round en cours (Redis).
   - Le client appelle cet endpoint au chargement, puis après chaque **join/leave** (détecté par Echo) pour se resynchroniser, et éventuellement une fois après un délai (ex. 2–5 s) après un `.joining()` pour rattraper les cas où l’event a été perdu.
   - **Avantage** : pas besoin de nouveaux events serveur si on a déjà un moyen d’obtenir la liste des présents ; correction simple après join.
   - **Coût** : une requête HTTP par join/leave (ou par sync de rattrapage).

3. **Hybride**
   - Event **RoomState** envoyé à chaque join/leave (liste + scores).
   - En complément, le client qui rejoint appelle déjà `GET /rooms/{id}/joined` ; on peut enrichir la réponse pour qu’elle renvoie aussi la **liste des utilisateurs actuellement dans la room** (en plus de round/track/scores). Ainsi, le nouveau joiner a immédiatement la bonne liste ; les autres s’appuient sur l’event RoomState.

### Flux proposé (résumé)

- **Backend**
  - Lors d’un join/leave sur le canal de la room, le serveur (via un listener Reverb ou un middleware/side-effect) émet un event **RoomState** (ou équivalent) avec `users` + `scores` (Redis pour le round en cours) + `roundId`.
  - Optionnel : endpoint `GET /rooms/{id}/state` ou enrichissement de `GET /rooms/{id}/joined` pour retourner la liste des présents + scores.
- **Front**
  - **Show.vue** : écoute **RoomState** et met à jour une ref unique, ex. `roomState = { users, scores, roundId }`.
  - **Ranking** (et tout composant qui a besoin de la liste) reçoit `roomState` (ou `users` + `scores` dérivés de cet état). Plus de reconstruction de liste à partir de `props.users` (Echo) + `props.data.scores` avec des watchers complexes.
  - Affichage : liste = `roomState.users`, triée par `roomState.scores[u.id]` ; plus de `userList` local à synchroniser avec deux sources.

### Ressources serveur

- Redis : inchangé (scores déjà lus pour le round).
- Un broadcast par join/leave (léger).
- Possibilité d’exposer la liste des présents via l’API Reverb/Pusher (si disponible) pour l’endpoint de state, sans stockage supplémentaire si on interroge le provider.

---

## Option B : Enrichir les events existants (correctif minimal)

### Idée

Sans refonte complète, rendre le système plus résilient en ne dépendant plus uniquement de la présence pour “voir” un nouveau joueur.

- **NewScore** : inclure dans le payload un **profil minimal** du joueur (id, name, photo, etc.) pour ce score. Côté client, si on reçoit un `NewScore` pour un `user_id` absent de la liste, on **ajoute** le joueur à partir de ce payload au lieu d’ignorer l’event.
- **RoomState / PlayerJoined** : ajouter un event **PlayerJoined** (et éventuellement **PlayerLeft**) émis côté serveur quand un utilisateur rejoint/quitte le canal, avec le payload utilisateur complet. Les clients mettent à jour leur liste à la réception.

Effet : même si `.joining()` Echo est en retard ou perdu, soit **PlayerJoined** donne la liste à jour, soit au pire le premier **NewScore** fait apparaître le joueur avec les bonnes infos.

### Ressources

- Un event de plus par join/leave (PlayerJoined / PlayerLeft ou RoomState).
- Payload NewScore un peu plus lourd (quelques champs user).

---

## Option C : Polling léger de rattrapage (complément)

### Idée

En complément de A ou B : après avoir reçu un `.joining()` (ou après un délai fixe post-mount), le client appelle une fois **GET /rooms/{id}/participants** (ou `state`) pour récupérer la liste officielle des présents + scores, et remplace sa liste locale.

- Permet de rattraper les cas où les events de présence ou RoomState ont été perdus.
- Si le polling est limité (une fois après join, ou toutes les 30 s max), le coût reste faible.

---

## Recommandation

- **Option A (RoomState comme source de vérité)** pour une base saine et prévisible : un seul event (ou endpoint) qui porte “qui est là” et “les scores”, le client affiche ça sans logique de fusion fragile.
- **Option B** peut être faite en parallèle ou en première étape rapide (NewScore enrichi + event PlayerJoined/RoomState) pour corriger l’invisibilité tout de suite.
- **Option C** en filet de sécurité (une requête de sync après join ou en heartbeat très espacé) si on veut une robustesse maximale sans trop de complexité.

---

## Résumé des changements proposés (si Option A)

| Zone | Actuel | Proposé |
|------|--------|--------|
| Liste des joueurs | Dérivée de Echo presence (`users`) + merge avec scores | Fournie par le serveur (event RoomState ou endpoint state) |
| Scores | Redis + events NewScore + watchers sur `data.scores` | Redis + RoomState + NewScore pour mises à jour en temps réel |
| Ranking.vue | `userList` synchronisé via 2–3 watchers (users, data.scores, NewScore) | Une source : `roomState` (users + scores) ; tri = dérivé simple |
| Join/Leave | Uniquement Echo `.here` / `.joining` / `.leaving` | Serveur émet RoomState (ou équivalent) à chaque join/leave ; client peut en plus réagir à Echo |
| Nouveau joiner | Reçoit round/track/scores via `GET /rooms/{id}/joined` | Idem + liste des présents dans la réponse (ou via RoomState juste après) |

Cela permet d’avoir un système **ultra fiable** (liste et scores venant du serveur), **performant** (Redis inchangé, un broadcast par join/leave) et **peu gourmand** (pas de polling continu, pas de logique lourde côté client).

Si tu valides cette direction (Option A avec ou sans B/C), on peut détailler les modifications fichier par fichier (backend + front) et passer à l’implémentation.
