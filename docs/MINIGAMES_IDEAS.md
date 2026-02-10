# Mini-jeux – idées et organisation du projet

Ce document liste les idées de mini-jeux et propose une structure modulaire pour les intégrer sans mélanger les fichiers avec le blind test principal.

---

## 1. Liste des idées de jeux

### Réutilisation maximale (audio + answers + tracks)

| Idée | Principe | Réutilisation | Difficulté |
|------|----------|---------------|------------|
| **Quiz à 4 choix (solo)** | Un extrait, 4 titres proposés (1 bon, 3 pioches dans d’autres tracks). Clic = validation. | Track, answers, liste de titres pour les leurres. | Facile |
| **« Qui a chanté ? »** | Même extrait, 4 noms d’artistes. | Track + artiste (answers/métadonnées) + artistes d’autres tracks. | Facile |
| **Blind test solo chronométré** | Même règle qu’en room : écoute, saisie ou choix. Pas de room, N tracks en enchaîné. | Rounds, tracks, check, scoring. | Facile |
| **Compléter le titre (trous)** | Titre avec trous : « Sweet ___ O’___ ». Le joueur complète. | Answers (titre) pour générer les trous. | Moyen (algo de trous) |

### Réutilisation partielle (audio + métadonnées)

| Idée | Principe | Réutilisation | Difficulté |
|------|----------|---------------|------------|
| **Deviner la décennie / l’année** | Un extrait, choix : décennie (70s, 80s…) ou année. | Audio + année de sortie (Sinclair, Deezer…). | Facile si année en BDD |
| **Deviner le genre** | Un extrait, choix parmi 4–6 genres. | Audio ; genre via playlist/catégorie ou API. | Facile si genre dispo |
| **Vrai / Faux** | Affirmations : « Sorti avant 1995 », « Artiste français », « &gt; 3 min ». | Métadonnées (année, durée, origine). | Facile |

### Variantes gameplay (même contenu)

| Idée | Principe | Réutilisation | Difficulté |
|------|----------|---------------|------------|
| **Mode streak (solo)** | Enchaîner les bonnes réponses ; une erreur = streak à 0. | Même flow blind test, compteur streak. | Facile |
| **Premier qui trouve (1v1)** | Deux joueurs, même track ; premier qui valide gagne le point. | Room + track + même check. | Moyen |
| **Snippet court vs long** | Choix difficulté : extrait 10 s ou 30 s. | Même `preview_url`, limite durée côté player. | Facile |

---

## 2. Organisation en « modules » dans le projet

Objectif : chaque mini-jeu vit dans des dossiers dédiés (backend + frontend), sans polluer Rooms/ ni les controllers principaux.

### 2.1 Backend (Laravel)

```
app/
├── Http/
│   └── Controllers/
│       └── Minigames/                    # Controllers dédiés aux mini-jeux
│           ├── MinigameController.php    # Index, hub, score global (optionnel)
│           ├── QuizController.php        # Quiz 4 choix
│           ├── WhoSangController.php     # Qui a chanté ?
│           ├── SoloBlindtestController.php
│           ├── FillTitleController.php
│           ├── DecadeController.php
│           ├── GenreController.php
│           └── ...
├── Services/
│   └── Minigames/                        # Logique métier commune
│       ├── MinigameScoreService.php      # Enregistrement des scores (points, type de jeu)
│       ├── TrackPickerService.php        # Piocher une track / 4 propositions (réutilisable)
│       └── ...
├── Models/
│   └── MinigameScore.php                 # (optionnel) Table dédiée aux scores mini-jeux
└── ...
```

- **Routes** : un fichier dédié, inclus dans `web.php` ou `bootstrap/app.php` :

```
routes/
├── web.php
├── minigames.php                         # Toutes les routes /minigames/...
└── ...
```

Exemple de préfixe : `Route::prefix('minigames')->name('minigames.')->group(...)`.

- **Form Requests** : si besoin, les garder dans `app/Http/Requests/Minigames/` (ex. `CheckQuizRequest.php`).

### 2.2 Frontend (Vue / Inertia)

```
resources/js/Pages/
├── Minigames/                            # Zone réservée aux mini-jeux
│   ├── Index.vue                         # Hub / liste des jeux (entrée depuis le menu)
│   ├── Layout.vue                        # Layout commun (optionnel : nav, titre « Mini-jeux »)
│   ├── Quiz/                             # Un sous-dossier par jeu
│   │   ├── Play.vue
│   │   └── partials/
│   │       └── ...
│   ├── WhoSang/
│   │   ├── Play.vue
│   │   └── partials/
│   ├── SoloBlindtest/
│   │   ├── Play.vue
│   │   └── partials/
│   ├── FillTitle/
│   ├── Decade/
│   ├── Genre/
│   ├── Streak/
│   └── shared/                           # Composants partagés entre mini-jeux
│       ├── MinigamePlayer.vue            # Lecteur audio (réutilise ou wrap le même que Rooms)
│       ├── MinigameScoreDisplay.vue
│       └── ...
├── Rooms/                                # Inchangé (blind test principal)
├── Home/
└── ...
```

- **Routes côté JS** : les noms de routes Laravel (ex. `minigames.quiz.play`) permettent des liens clairs depuis le hub.
- **Menu** : un lien « Mini-jeux » vers `route('minigames.index')` (ou équivalent) pour garder la zone bien distincte.

### 2.3 Base de données (si besoin)

- Soit **réutiliser** le système de points existant (scores, niveaux) avec un champ ou une table de liaison indiquant la **source** (ex. `activity_type` : `room_round` vs `minigame_quiz`, `minigame_decade`, etc.).
- Soit table dédiée type `minigame_scores` : `user_id`, `minigame_type` (string ou enum), `score`, `metadata` (JSON), `created_at`, pour garder l’historique par type de jeu et agréger un « score total mini-jeux » ou alimenter le niveau.

### 2.4 Récap des emplacements

| Élément | Emplacement |
|--------|-------------|
| Routes | `routes/minigames.php` (préfixe `minigames`) |
| Controllers | `app/Http/Controllers/Minigames/*` |
| Logique métier | `app/Services/Minigames/*` |
| Modèles dédiés | `app/Models/Minigame*.php` |
| Pages Vue | `resources/js/Pages/Minigames/` (sous-dossiers par jeu) |
| Composants partagés | `resources/js/Pages/Minigames/shared/` |
| Layout | `resources/js/Pages/Minigames/Layout.vue` (optionnel) |

Ainsi, tout ce qui touche aux mini-jeux reste regroupé et séparé du blind test en room.
