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

## Production


## Roadmap

- TODO v2.0 :
	- Messages réponses
	- Statistiques
	- Webhooks discord
	- Attente pendant que le morceau est en lecture
	- ✅ Mon compte
	- ✅ Traduction et templates des mails
	- ✅ Envoyer une suggestion (modos room)
	- ✅ Empty playlist
	- ✅ Fix Music provider api
	- ✅ Socialite google
	- ✅ Finnished round modal
	- ✅ Sendinblue
	- ✅ Donate
	- ✅ Pages
	- ✅ Réinitialisation de mot de passe
	- ✅ Affichage des scores
	- ✅ Affichage des réponses
	- ✅ Rooms Images : mosaics from album (to prevent random images from users)
	- ✅ Avatar sur la timeline quand bonne réponse
	- ✅ Bloquer des noms reservés à l'inscription
	- ✅ Chat
	- ✅ Migration DB
	- ✅ Team
	- ✅ Rankings
	- ✅ Recherche

- TODO v2.1
	- Answers Card : réponses users surlignées
	- Room control
	- Modération (page)
	- SEO
	- Chat emojis
	- Betters answers messages (wrong, almost, right)
	- Room control
	- Ban IPs
	- Rooms Images : custom for pro
	- Better translations (confirm js)
	- Check duplicated names from socialite
