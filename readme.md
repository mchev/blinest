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

- New rooms
	- Facile
	- Speed

- TODO :
	- ✅ Sendinblue
	- ✅ Donate
	- Pages
	- Messages réponses
	- Answers : réponses users
	- Statistiques
	- Réinitialisation de mot de passe
	- Mon compte
	- Modération
	- Room control
	- Rooms Images : custom for pro
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
