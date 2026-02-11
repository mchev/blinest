# Architecture temps réel (rooms) – refonte from scratch

Document de référence : comment gérer présence, scores, home et événements si on repart de zéro.

---

## 1. Principes

- **Une source de vérité par type de donnée** : pas de duplication entre Redis et Reverb pour la même info.
- **Un type de channel par usage** : presence pour “dans la room”, public pour “preview home”.
- **Côté client : une souscription par contexte** (page room = un `Echo.join`, home = un `Echo.channel` par vignette).
- **Scores découplés de la présence** : enregistrés dès qu’un point est marqué, finalisation basée sur Redis uniquement.

---

## 2. Qui est dans la room (liste + count)

### Source de vérité pour la liste “dans la room”

- **Reverb presence channel** `rooms.{roomId}` uniquement.
- Côté client : `Echo.join('rooms.X')` → `.here()`, `.joining()`, `.leaving()`.
- Pas de liste “joueurs” dans un event broadcast (ex. `RoomState`) : la liste vient uniquement de la presence.

### Count pour la home (et autres vues publiques)

- Reverb ne notifie pas le serveur quand quelqu’un join/leave une presence channel (sans webhooks).
- Donc le **count** doit être maintenu côté app :
  - Le client envoie **join** / **leave** via HTTP (`presence-joined`, `presence-left`).
  - Le serveur met à jour un store (ex. Redis) et diffuse le nouveau count (et la progression) aux clients qui regardent la home.

Options possibles :

**A) Redis + heartbeat + prune (ce qu’on a aujourd’hui)**

- Redis : sorted set `room:{id}:members`, score = `last_seen`.
- Join / leave : HTTP → `ZADD` / `ZREM`.
- Heartbeat (ex. toutes les 25 s) : HTTP → `ZADD` avec `now()`.
- À chaque lecture du count : prune `ZREMRANGEBYSCORE` (ex. > 90 s) puis `ZCARD`.
- Avantage : count qui se corrige tout seul si quelqu’un part sans appeler leave (crash, fermeture brutale).
- Inconvénient : un peu de logique (heartbeat + prune).

**B) Reverb webhooks (si dispo)**

- Reverb envoie “subscribed” / “unsubscribed” sur la presence channel à notre backend.
- Backend met à jour un count (Redis ou DB) et broadcast.
- Plus besoin de heartbeat ni de prune côté app ; la liste Reverb reste la seule source pour “qui est là”, le count en est dérivé côté serveur.

Recommandation : **A** tant que Reverb ne fournit pas de webhooks presence ; **B** si tu peux les activer.

---

## 3. Événements “room” (tracks, scores, round)

- **Un seul canal pour tout ce qui se passe dans la room** : la **presence channel** `rooms.{roomId}`.
- Tous les events (TrackPlayed, RoundStarted, RoundFinished, NewScore, RoomState, etc.) sont broadcast sur **PresenceChannel** `rooms.{roomId}`.
- Côté client : une seule souscription `Echo.join('rooms.X')` sur la page room, avec `.here`/`.joining`/`.leaving` + `.listen('…')` pour chaque event.
- Aucun `Echo.channel('rooms.X')` (public) pour ces events : sinon les clients sur la presence ne les reçoivent pas (ou l’inverse), d’où les bugs qu’on a eus.

Résumé :

- Liste des joueurs dans la room : **uniquement** `.here` / `.joining` / `.leaving`.
- Scores / round / tracks : **uniquement** les events sur la presence channel.

---

## 4. Home (vignettes rooms) : count + progression

- **Contrainte** : utilisateurs **non connectés** doivent voir count + progression (track actuel, barre de progression).
- Donc **pas** d’auth : pas de `Echo.join` pour la home.

Design :

- **Channel publique** : `room.public.{roomId}`.
- **Un seul type d’event** : `RoomPublicState` avec par ex. :
  - `memberCount`
  - `currentTrackIndex`, `tracksByRound`, `isPlaying`
- Backend envoie `RoomPublicState` quand :
  - join/leave (count change),
  - round start / track played / round finished (progression change).
- Côté client (home) : `Echo.channel('room.public.' + roomId).listen('RoomPublicState', …)`.
- Pas de private channel ni de whisper pour le count : tout passe par cette channel publique.

Données initiales (SSR) : le count et la progression au chargement de la page viennent du même endroit que ce qui alimente `RoomPublicState` (ex. Redis pour le count, room/round pour la progression), pour rester cohérent.

---

## 5. Scores (aucune perte si l’user quitte)

- Les scores ne dépendent **pas** de la présence dans la room.
- À chaque bonne réponse : **écriture immédiate** dans Redis (ex. `round:{roundId}:scores:{userId}` + podium).
- En fin de round : job de finalisation qui lit **tous** les scores du round dans Redis (`getAllScores`), crée standings / ELO / total scores pour **tous** les user_id qui ont une clé, qu’ils soient encore dans la room ou non.
- Pas de nettoyage des scores Redis quand un user quitte la room ; nettoyage uniquement après finalisation du round.

Donc : **refaire from scratch ne change rien ici** – garder ce modèle (Redis pendant la partie, finalisation globale).

---

## 6. Côté client (page room)

- **Un seul `Echo.join('rooms.' + roomId)`** au mount.
  - `.here(users)` → liste initiale (avec fallback “moi” si seul).
  - `.joining(u)` / `.leaving(u)` → mise à jour de la liste.
  - `.listen('RoomState', …)` → uniquement scores/roundId (pas la liste).
  - `.listen('TrackPlayed', …)`, `RoundStarted`, `RoundFinished`, `NewScore`, etc.
- **Un seul `Echo.leave`** au démontage de la page (pas les enfants).
- **Heartbeat** (si option A count) : `setInterval` ~25 s → `POST presence-joined` ; clear au démontage.
- **Leave** : `beforeunload` (sendBeacon) + `onBeforeUnmount` (axios) vers `presence-left`.

Composants enfants (Player, UserInput, etc.) : soit ils écoutent sur la **même** presence (e.g. `Echo.join` sans appeler `Echo.leave` eux-mêmes), soit ils ne s’abonnent pas et reçoivent tout via props/state du parent. Éviter plusieurs `Echo.leave` sur la même channel.

---

## 7. Récap “si je refais tout”

| Besoin              | Source de vérité        | Où c’est mis à jour / lu                    |
|---------------------|-------------------------|---------------------------------------------|
| Liste dans la room  | Reverb presence         | .here / .joining / .leaving                 |
| Count (home)        | Redis (option A)        | HTTP join/leave + heartbeat, prune avant count |
| Progression (home)  | Room + round (DB)       | Events round/track → RoomPublicState        |
| Scores pendant part | Redis par round         | addScore à chaque point                     |
| Scores fin de part  | Redis → DB              | ProcessRoundFinalization lit tout Redis     |

- **Channels** :  
  - `rooms.{id}` = presence (auth), tout ce qui se passe dans la room.  
  - `room.public.{id}` = public, uniquement `RoomPublicState` pour la home.
- **Pas de** : liste joueurs dans un event, `Echo.channel('rooms.X')` pour les events room, count basé uniquement sur la presence sans HTTP/heartbeat (sauf si webhooks Reverb).

Ainsi, en refaisant from scratch, on garde la même séparation (présence Reverb vs count Redis vs scores Redis) et on évite les doublons et les channels mélangés (public vs presence).
