# Proposition d'Optimisation des Performances

## Problèmes Identifiés

### 1. **Scores Individuels (Table `scores`)**
- **Problème** : Chaque réponse crée un enregistrement, puis tous sont supprimés après le round
- **Impact** : Millions d'écritures/suppressions inutiles
- **Solution** : Utiliser Redis pour les scores en temps réel, ne sauvegarder que les agrégations finales

### 2. **RoundTrackListener**
- **Problème** : Un enregistrement par track écoutée par joueur
- **Impact** : Beaucoup d'écritures pendant les parties
- **Solution** : Utiliser Redis avec compteurs, sauvegarder seulement à la fin du round

### 3. **Jobs en Queue (ProcessAddScoreToTotalScore)**
- **Problème** : Un job par score = des milliers de jobs par round
- **Impact** : Surcharge de la queue, latence
- **Solution** : Batch les scores et traiter en une seule fois à la fin du round

### 4. **Calculs en Temps Réel**
- **Problème** : `userScore()` fait `SUM()` à chaque appel, `usersPodium()` fait des agrégations complexes
- **Impact** : Requêtes lourdes pendant les parties
- **Solution** : Utiliser Redis pour maintenir les scores en temps réel

### 5. **TotalScores**
- **Problème** : `updateOrCreate` + `increment` = 2 requêtes par score
- **Impact** : Beaucoup de requêtes pour incrémenter
- **Solution** : Batch les incréments et les appliquer en une fois

## Architecture Proposée

### Phase 1 : Redis pour les Scores en Temps Réel

#### Structure Redis
```
round:{round_id}:scores:{user_id} = score_total (float)
round:{round_id}:tracks:{user_id} = set de track_ids écoutées
round:{round_id}:podium = sorted set (user_id => score_total)
round:{round_id}:listeners:{user_id} = hash {track_id => timestamp}
```

#### Avantages
- **Lecture ultra-rapide** : O(1) pour récupérer un score
- **Pas d'écritures DB** pendant le round
- **Podium en temps réel** : Redis sorted set
- **Auto-expiration** : TTL sur les clés Redis

### Phase 2 : Agrégation en Batch à la Fin du Round

#### Processus
1. **Pendant le round** : Tout est en Redis
2. **À la fin du round** :
   - Récupérer tous les scores depuis Redis
   - Créer les `RoundStanding` en batch
   - Calculer ELO en batch
   - Mettre à jour `TotalScores` en batch (une requête par user/room)
   - Nettoyer Redis

#### Avantages
- **Une seule transaction DB** par round
- **Pas de jobs individuels** : tout en batch
- **Performance** : Insertions en bulk

### Phase 3 : Optimisation des Requêtes

#### Scores
- **Avant** : `SUM(score)` à chaque appel
- **Après** : Lecture depuis Redis (O(1))

#### Podium
- **Avant** : `GROUP BY` + `SUM()` + `ORDER BY`
- **Après** : `ZREVRANGE` sur Redis sorted set (O(log N))

#### Tracks Écoutées
- **Avant** : Comptage depuis `round_track_listeners`
- **Après** : `SCARD` sur Redis set (O(1))

## Implémentation

### 1. Service Redis pour les Scores

```php
class RoundScoreService
{
    public function addScore(int $roundId, int $userId, float $score): void
    {
        $key = "round:{$roundId}:scores:{$userId}";
        Redis::incrbyfloat($key, $score);
        Redis::expire($key, 3600); // 1h TTL
        
        // Mettre à jour le podium
        $podiumKey = "round:{$roundId}:podium";
        Redis::zincrby($podiumKey, $score, $userId);
        Redis::expire($podiumKey, 3600);
    }
    
    public function getUserScore(int $roundId, int $userId): float
    {
        return (float) Redis::get("round:{$roundId}:scores:{$userId}") ?? 0;
    }
    
    public function getPodium(int $roundId, int $limit = 10): array
    {
        return Redis::zrevrange("round:{$roundId}:podium", 0, $limit - 1, 'WITHSCORES');
    }
    
    public function markTrackListened(int $roundId, int $userId, int $trackId): void
    {
        $key = "round:{$roundId}:tracks:{$userId}";
        Redis::sadd($key, $trackId);
        Redis::expire($key, 3600);
    }
    
    public function getTracksListenedCount(int $roundId, int $userId): int
    {
        return Redis::scard("round:{$roundId}:tracks:{$userId}") ?? 0;
    }
    
    public function getAllScores(int $roundId): array
    {
        $pattern = "round:{$roundId}:scores:*";
        $keys = Redis::keys($pattern);
        $scores = [];
        
        foreach ($keys as $key) {
            $userId = (int) str_replace("round:{$roundId}:scores:", '', $key);
            $scores[$userId] = (float) Redis::get($key);
        }
        
        return $scores;
    }
    
    public function cleanup(int $roundId): void
    {
        $pattern = "round:{$roundId}:*";
        $keys = Redis::keys($pattern);
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }
}
```

### 2. Modification de RoundController

```php
// Au lieu de créer un Score en DB
$savedScore = $user->scores()->create([...]);

// Utiliser Redis
RoundScoreService::addScore($round->id, $user->id, $score);

// Pour le broadcast, récupérer depuis Redis
$total = RoundScoreService::getUserScore($round->id, $user->id);
```

### 3. Modification de Round::userScore()

```php
public function userScore(User $user): float
{
    // Si le round est en cours, utiliser Redis
    if ($this->is_playing && !$this->finished_at) {
        return RoundScoreService::getUserScore($this->id, $user->id);
    }
    
    // Sinon, utiliser les standings (déjà agrégés)
    $standing = $this->standings()->where('user_id', $user->id)->first();
    return $standing ? (float) $standing->total_score : 0;
}
```

### 4. Modification de Round::usersPodium()

```php
public function usersPodium()
{
    // Si le round est en cours, utiliser Redis
    if ($this->is_playing && !$this->finished_at) {
        $podium = RoundScoreService::getPodium($this->id);
        // Convertir en format compatible
        return collect($podium)->map(function ($score, $userId) {
            return (object) [
                'user_id' => $userId,
                'total' => $score,
            ];
        });
    }
    
    // Sinon, utiliser les standings
    return $this->standings()
        ->select('user_id', 'total_score as total')
        ->orderByDesc('total_score');
}
```

### 5. Job d'Agrégation en Batch

```php
class ProcessRoundFinalization implements ShouldQueue
{
    public function handle(RoundScoreService $scoreService, EloService $eloService): void
    {
        $round = $this->round;
        
        // 1. Récupérer tous les scores depuis Redis
        $scores = $scoreService->getAllScores($round->id);
        $tracksListened = $scoreService->getAllTracksListened($round->id);
        
        // 2. Créer les standings en batch
        $standings = [];
        foreach ($scores as $userId => $totalScore) {
            $tracksCount = $tracksListened[$userId] ?? 0;
            $standings[] = [
                'round_id' => $round->id,
                'user_id' => $userId,
                'total_score' => $totalScore,
                'tracks_listened_count' => $tracksCount,
                // ... autres champs
            ];
        }
        
        RoundStanding::insert($standings);
        
        // 3. Calculer ELO en batch
        $eloService->updateElosForRound($round);
        
        // 4. Mettre à jour TotalScores en batch
        $this->updateTotalScoresBatch($round, $scores);
        
        // 5. Nettoyer Redis
        $scoreService->cleanup($round->id);
    }
    
    private function updateTotalScoresBatch(Round $round, array $scores): void
    {
        $room = $round->room;
        $updates = [];
        
        foreach ($scores as $userId => $score) {
            $updates[] = [
                'totalscorable_type' => User::class,
                'totalscorable_id' => $userId,
                'room_id' => $room->id,
                'score' => $score,
            ];
        }
        
        // Utiliser INSERT ... ON DUPLICATE KEY UPDATE pour batch update
        DB::table('total_scores')->insertOrUpdate($updates, ['score' => DB::raw('score + VALUES(score)')]);
    }
}
```

### 6. Suppression de la Table `scores`

- **Option 1** : Ne plus créer de scores individuels, seulement les standings
- **Option 2** : Garder pour historique mais avec TTL (suppression après X jours)
- **Option 3** : Archiver dans une table séparée pour analytics

### 7. Optimisation de RoundTrackListener

- Utiliser Redis set au lieu de table DB
- Sauvegarder seulement le count dans les standings
- Supprimer la table `round_track_listeners` ou l'utiliser seulement pour analytics

## Bénéfices Attendus

### Performance
- **Réduction de 90%+ des écritures DB** pendant les parties
- **Latence réduite** : Redis O(1) vs DB queries
- **Pas de jobs individuels** : tout en batch
- **Moins de charge serveur** : pas de millions de records temporaires

### Scalabilité
- **Redis scalable** : peut gérer des milliers de rounds simultanés
- **Batch processing** : efficace même avec beaucoup de joueurs
- **Auto-cleanup** : TTL sur Redis évite l'accumulation

### Maintenance
- **Code plus simple** : moins de jobs, moins de requêtes
- **Debugging plus facile** : tout centralisé dans Redis
- **Monitoring** : métriques Redis faciles à suivre

## Migration

### Étape 1 : Implémenter RoundScoreService
- Créer le service
- Tester avec un round
- Vérifier les performances

### Étape 2 : Migrer RoundController
- Utiliser Redis au lieu de DB pour les scores
- Garder la compatibilité avec l'ancien système

### Étape 3 : Migrer ProcessRoundElo
- Utiliser les données Redis
- Créer ProcessRoundFinalization

### Étape 4 : Nettoyer
- Supprimer ProcessAddScoreToTotalScore (remplacé par batch)
- Supprimer la création de scores individuels
- Optionnel : supprimer la table `scores` après migration complète

## Estimation

- **Réduction des écritures DB** : ~95%
- **Réduction de la latence** : ~80%
- **Réduction de la charge serveur** : ~70%
- **Temps de développement** : 2-3 jours
- **Risque** : Faible (compatibilité maintenue pendant la transition)

