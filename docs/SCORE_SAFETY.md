# Garanties de sécurité des scores

## 🔒 Protection contre la perte de données

Le système ELO a été conçu avec plusieurs mécanismes de sécurité pour garantir qu'**aucun score ne sera perdu** lors de la mise en production.

## ✅ Garanties principales

### 1. Transaction atomique
- **Tout le processus se fait dans une transaction unique** (`DB::transaction`)
- Si une erreur survient à n'importe quel moment, **toute l'opération est annulée**
- Les scores individuels ne sont **jamais supprimés** si les standings n'ont pas été créés avec succès

### 2. Ordre d'exécution sécurisé
Le processus suit cet ordre strict :

1. ✅ **Vérification** que le round est terminé
2. ✅ **Lock** sur le round pour éviter les race conditions
3. ✅ **Vérification** qu'il n'y a pas déjà de standings (évite les doublons)
4. ✅ **Calcul** du `total_score` depuis les scores individuels (via `usersPodium()`)
5. ✅ **Création** des standings avec le `total_score` sauvegardé
6. ✅ **Vérification** que tous les standings ont un `total_score` valide
7. ✅ **Suppression** des scores individuels (seulement si tout est OK)

### 3. Vérifications avant suppression
Avant de supprimer les scores, le système vérifie :
- ✅ Que les standings ont bien été créés
- ✅ Que chaque standing a un `total_score` valide (non null)
- ✅ Si une vérification échoue, **la suppression est annulée** et une exception est levée

### 4. Protection contre les doublons
- Un **lock** (`lockForUpdate()`) empêche deux jobs de traiter le même round simultanément
- Vérification que des standings n'existent pas déjà avant de créer de nouveaux

### 5. Logs détaillés
Toutes les opérations sont loggées :
- Création des standings
- Nombre de scores supprimés
- Erreurs éventuelles

## 🛡️ Scénarios de sécurité

### Scénario 1 : Erreur avant création des standings
- **Résultat** : Les scores individuels restent intacts
- **Action** : Le job peut être relancé sans perte de données

### Scénario 2 : Erreur pendant la création des standings
- **Résultat** : Transaction annulée, scores intacts
- **Action** : Le job peut être relancé

### Scénario 3 : Erreur après création des standings mais avant suppression
- **Résultat** : Transaction annulée, standings supprimés, scores intacts
- **Action** : Le job peut être relancé (les standings seront recréés)

### Scénario 4 : Erreur pendant la suppression
- **Résultat** : Transaction annulée, standings intacts, scores intacts
- **Action** : Le job peut être relancé (les standings existants seront détectés et le job s'arrêtera)

## 🔍 Vérification post-déploiement

### Commande de vérification
Une commande a été créée pour vérifier l'intégrité des données :

```bash
php artisan scores:verify-integrity
```

Cette commande :
- ✅ Vérifie tous les rounds terminés
- ✅ Détecte les incohérences entre scores et standings
- ✅ Signale les rounds non traités
- ✅ Peut corriger automatiquement certains problèmes

### Utilisation
```bash
# Vérifier tous les rounds
php artisan scores:verify-integrity

# Vérifier un round spécifique
php artisan scores:verify-integrity --round-id=123

# Vérifier et corriger automatiquement
php artisan scores:verify-integrity --fix
```

## 📊 Tests de sécurité

Le système inclut des tests automatisés qui vérifient :
- ✅ Que les `total_score` sont préservés après suppression des scores individuels
- ✅ Que les scores ne sont pas supprimés si les standings ne sont pas créés
- ✅ Que les race conditions sont évitées
- ✅ Que les transactions fonctionnent correctement

## 🚀 Migration des données existantes

Pour migrer les scores historiques vers les standings :

```bash
php artisan scores:migrate-to-standings
```

Cette commande :
- ✅ Traite les rounds un par un
- ✅ Utilise le même processus sécurisé que les nouveaux rounds
- ✅ Supporte le mode dry-run pour tester sans modifier
- ✅ Peut être exécutée en plusieurs fois (batch processing)

## ⚠️ Points d'attention

### Avant la mise en production
1. ✅ Exécuter `scores:verify-integrity` sur la base de production
2. ✅ Tester la migration sur une copie de la base de production
3. ✅ Vérifier les logs après les premiers rounds traités

### Pendant la mise en production
1. ✅ Surveiller les logs pour détecter les erreurs
2. ✅ Exécuter `scores:verify-integrity` régulièrement
3. ✅ Vérifier que les `total_score` dans les standings correspondent aux anciens totaux

### En cas de problème
1. ✅ Arrêter le traitement (les jobs en queue continueront mais les nouveaux seront bloqués)
2. ✅ Exécuter `scores:verify-integrity` pour identifier les problèmes
3. ✅ Corriger avec `--fix` si possible
4. ✅ Reprendre le traitement une fois corrigé

## 📝 Résumé

**Aucun score ne sera perdu** car :
1. Le `total_score` est calculé et sauvegardé **avant** la suppression
2. Tout se fait dans une **transaction atomique**
3. Des **vérifications** empêchent la suppression si quelque chose ne va pas
4. Des **tests automatisés** garantissent le bon fonctionnement
5. Une **commande de vérification** permet de détecter les problèmes

Le système est conçu pour être **fail-safe** : en cas d'erreur, les données restent intactes.

