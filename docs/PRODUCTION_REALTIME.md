# Mise en production : temps réel (présence, joueurs, réponses)

Les fonctionnalités suivantes dépendent de **Reverb** (WebSockets) et **Redis** :

- Liste des joueurs connectés dans une room
- Compteur de joueurs sur les cartes de la home
- Mise à jour des réponses en direct pendant une partie
- Événements RoomState, NewScore, etc.

## 1. Variables d’environnement

### Côté serveur (Laravel)

Dans `.env` de production :

```env
BROADCAST_CONNECTION=reverb
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# Redis (présence + queue)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Reverb (serveur WebSocket)
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=blinest.com
REVERB_PORT=443
REVERB_SCHEME=https
```

- `REVERB_HOST` : domaine public (sans `wss://`), celui que le navigateur pourra joindre.
- En production, `REVERB_SCHEME` doit être `https` et le port souvent `443`.

### Côté front (Vite) – **critique**

Les variables `VITE_REVERB_*` sont **injectées au moment du build** (`npm run build`). Elles ne sont pas lues au runtime.

Tu **dois** :

1. Définir ces variables **avant** de lancer le build (dans `.env` ou `.env.production` sur la machine qui fait le build) :

```env
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="blinest.com"
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https
```

2. Lancer le build **avec ce .env** :

```bash
npm run build
```

3. Déployer les fichiers générés dans `public/build/` (et le reste de l’app).

Si le build est fait **en local** avec un `.env` qui pointe vers `localhost`, le JS compilé contiendra `localhost` et en production les WebSockets ne pourront pas se connecter.

**En résumé** : soit tu builds sur le serveur de prod (où `.env` a les bonnes valeurs), soit tu builds en local avec un `.env.production` qui contient les `VITE_REVERB_*` de production, puis tu déploies ce build.

## 2. Reverb en production

Le serveur Reverb doit tourner en continu, par exemple :

```bash
php artisan reverb:start
```

En production, il faut le lancer via un process manager (Supervisor, systemd, ou l’outil de ton hébergeur) pour qu’il redémarre en cas de crash.

Exemple Supervisor :

```ini
[program:reverb]
command=php /chemin/vers/artisan reverb:start
directory=/chemin/vers/projet
autostart=true
autorestart=true
user=www-data
```

Si Reverb n’est pas démarré, les WebSockets ne fonctionnent pas → plus de liste de joueurs, plus de mises à jour des réponses en direct.

## 3. Redis

- Le **présence** (qui est dans quelle room) et le **compteur sur la home** utilisent Redis.
- Vérifier que Redis est installé, démarré et que `REDIS_*` dans `.env` pointent vers la bonne instance (même Redis que pour la queue si tu en utilises un).

Sans Redis correct :

- Les appels `presence-joined` / `presence-left` peuvent échouer ou ne pas persister.
- La home peut ne pas afficher les compteurs (ou les afficher à 0 si tu ajoutes des fallbacks).

## 4. Après déploiement

1. **Vider les caches Laravel** (config peut contenir l’ancien `BROADCAST_CONNECTION`) :

```bash
php artisan config:clear
php artisan config:cache
```

2. **Vérifier le build** : dans les outils de développement du navigateur (onglet Network ou Console), vérifier que les requêtes WebSocket partent vers le bon hôte (ex. `wss://blinest.com`), et qu’il n’y a pas d’erreur de connexion.

3. **Tester** : ouvrir une room, vérifier que la liste des joueurs se met à jour et que les réponses restent visibles en direct.

## Résumé des symptômes

| Symptôme | Cause probable |
|----------|-----------------|
| "No players yet" alors que des joueurs sont dans la room | En prod, le callback `.here()` du canal présence peut ne pas être émis par Reverb. Le code appelle maintenant `joining()` dès que `Echo.join()` réussit (`.then()`). Rebuild du front + redéploiement. |
| Compteur à 0 sur la home, liste joueurs vide | Redis indisponible ou `VITE_*` pas utilisées au build |
| Pas de mise à jour en direct, réponses qui “disparaissent” | Reverb non démarré ou `BROADCAST_CONNECTION` pas à `reverb` |
| WebSocket vers localhost en prod | Build fait avec un .env où `VITE_REVERB_HOST=localhost` → refaire le build avec les `VITE_REVERB_*` de prod |
