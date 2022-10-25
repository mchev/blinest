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

## Migration

- ✅ New answers type
- ✅ Mail a chaque connexion provider
- ✅ Mentions légales
- ✅ Politique de confidentialité
- ✅ Stats
- Git branch old

## Roadmap

 - v2.1
 	- ✅ Flamme 18%
 	- ✅ Avatar dans le chat
 	- Bulle utilisateur sur le player quand bonne réponse
 	- Couleur badge réponse
 	- ✅ Les articles sont encore necessaires dans les réponses
 	- Lien de connexion
 	- Bad words dans le mot
 	- teams js sort error : https://github.com/mchev/blinest/issues/15

- TODO v2.0 :
	- Pubs ?
	- Answers Card : réponses users surlignées
	- Fix iphone sound
	- ✅ Statistiques
	- ✅ Webhooks discord
	- ✅ Messages réponses
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
	- Attente pendant que le morceau est en lecture
	- Room control
	- Modération (page)
	- SEO
	- Chat emojis
	- Betters answers messages (wrong, almost, right)
	- Ban IPs
	- Rooms Images : custom for pro
	- Better translations (confirm js)
	- Check duplicated names from socialite
