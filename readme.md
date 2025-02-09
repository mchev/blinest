# Blinest

## Developpement

### Requirements

Blinest uses WebSockets for real-time communication. Laravel Reverb is used as the WebSocket server.

Go to your working directory and:

```console
composer install
```

```console
php artisan key:generate
```

```console
npm install
```

```console
php artisan migrate:fresh --seed
```

### Start developing
```console
npm run dev
```
```console
php artisan reverb:start
```
```console
php artisan horizon
```
