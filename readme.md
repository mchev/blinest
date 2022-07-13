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
	- ✅ Affichage des scores
	- ✅ Affichage des réponses
	- Avatar sur la timeline quand bonne réponse
	- Parametrer les bonnes réponses sur la room pour affichage timeline ?
	- ✅ Bloquer des noms reservés à l'inscription
	- Modération
	- Room control
	- Chat
	- ✅ Migration DB
	- Team
	- Rankings
	- ✅ Recherche
