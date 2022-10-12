# Blinest

## Developpement

### Requirements

Blinest use Websockets a lot. So you will have to install soketi globaly :

https://docs.soketi.app/getting-started/installation/cli-installation

Then go to your working directory and :

```
composer install
```

```
php artisan key::generate
```

```
npm install
```

```
php artisan migrate:fresh --seed
```

### Start developing
```
npm run dev
```
```
soketi start
```
```
php artisan queue:work
```

## Production


## Roadmap

- TODO v2.0 :
	- Messages réponses
	- Answers : réponses users
	- Statistiques
	- Webhooks discord
	- Mon compte
	- Modération
	- Room control
	- Traduction et templates des mails
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
	- Chat emojis
	- Betters answers messages (wrong, almost, right)
	- Room control
	- Ban IPs
	- Rooms Images : custom for pro
	- Better translations (confirm js)
