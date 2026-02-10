# Pistes d'amélioration globales du jeu

## Fiabilité & robustesse

### 1. Réponse explicite sur `check` quand le round/track ne correspond pas
**Où** : `RoundController::check()`  
**Problème** : Si `! $round->finished_at && $round->tracks[$round->current - 1] === $track->id` est faux (round terminé, mauvaise track, etc.), la méthode ne retourne rien → le client reçoit une réponse vide (200 ou 0).
**Piste** : Toujours retourner une réponse JSON explicite, par ex. `422` ou `409` avec `{ "error": "round_ended" }` ou `"track_mismatch"`, et gérer côté front (message utilisateur, réinitialisation de l’état).

### 2. Rate limiting sur la soumission des réponses
**Où** : `POST rounds/{round}/tracks/{track}/check`  
**Problème** : Pas de throttle sur cette route → risque de spam ou de surcharge.
**Piste** : Ajouter un throttle raisonnable, ex. `throttle:60,1` (60 requêtes/min/joueur) pour limiter les abus tout en gardant un jeu fluide.

### 3. Reconnexion / résilience WebSocket
**Où** : `Show.vue` (Echo)  
**Piste** : En cas de déconnexion Echo (réseau, Reverb redémarré), prévoir une réinscription au canal et un rappel `presence-joined` (et éventuellement refetch de l’état room) pour resynchroniser la liste et les scores sans recharger la page.

---

## UX

### 4. Retour visuel sur erreur de soumission
**Où** : `UserInput.vue`  
**Piste** : En cas d’erreur réseau ou 429 (throttle), afficher un court message (“Problème de connexion, réessaie”) au lieu de seulement restaurer le texte, pour que le joueur comprenne pourquoi la réponse ne part pas.

### 5. Indicateur de connexion
**Où** : Barre ou header de la room  
**Piste** : Petit indicateur “Connecté” / “Reconnexion…” basé sur l’état d’Echo (ou un heartbeat), pour rassurer en cas de latence.

### 6. Gestion du “joueur seul”
**Où** : Room / classement  
**Piste** : Message ou état dédié quand il n’y a qu’un joueur (“En attente d’autres joueurs…” ou “Tu es seul pour l’instant”) pour éviter l’impression de bug.

---

## Performance & coût

### 7. Batch du comptage de présence sur la home
**Où** : `HomeController::withPresenceCount()`  
**Piste** : Au lieu de N appels Redis `SCARD` (un par room), faire un pipeline Redis ou une méthode du type `getMemberCountsForRooms(Collection $rooms): array` pour un seul aller-retour et réduire la latence de la home.

### 8. Réduire les listeners Echo par room
**Où** : `Room.vue` et `FeaturedRoom.vue` (home)  
**Piste** : Chaque carte room s’abonne à `Echo.private('room.count.{id}')`. Pour beaucoup de rooms, ça fait beaucoup de canaux. Possibilité d’un canal unique “home” qui diffuse les counts des rooms (ou de ne s’abonner qu’aux rooms visibles / au viewport).

---

## Qualité de code & maintenabilité

### 10. Typage / props Vue
**Où** : Composants Rooms (Show, Ranking, etc.)  
**Piste** : Définir des types plus stricts pour les props (`roomState`, `room`, etc.) avec des objets/types TypeScript ou au minimum des `validator` dans `defineProps` pour éviter des erreurs silencieuses (ex. `roomState.users` undefined).

---

## Sécurité & modération

### 13. Limiter les appels `presence-joined` / `presence-left`
**Où** : Routes `presence-joined` / `presence-left`  
**Piste** : Throttle léger (ex. 10 req/min par user) pour éviter qu’un client bugué ne spamme et fasse boucler les RoomState.

---
