# Blinest

## Developpement

### Requirements

Blinest use Websockets a lot. So you will have to install soketi globaly :

https://docs.soketi.app/getting-started/installation/cli-installation

Then go to your working directory and :

```console
composer install
```

```console
php artisan key::generate
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
soketi start
```
```console
php artisan horizon
```

## Roadmap

- [x] Better translations
- [ ] Profil pages
- [ ] Better way to wait while a track is playing (replace loading by a countdown or something else)
- [ ] Chat emojis
- [ ] Light mode
- [ ] Moderation : rooms name edition
- [ ] Pro/Premium rooms