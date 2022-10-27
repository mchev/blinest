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

- v2.2
	- Import playlists from provider
	- Profil pages
	- Pubs ?
	- Answers Card : réponses users surlignées
	- Fix iphone sound
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
	- Clean rounds with no scores?

 - v2.1
 	- ✅ slugs
 	- recherche d'extaits provider à améliorer
 	- ✅ Fix bug possibilité d'entrer une réponse après la fin du morceaux
 	- sitemap
  	- light mode mal voyant
 	- moderation des noms de room
 	- Confirm alerte modération
 	- ✅ Vider le champ de réponse à la fin d'un extrait
 	- ✅ Flamme 18%
 	- ✅ Avatar dans le chat
 	- ✅ Bulle utilisateur sur le player quand bonne réponse
 	- ✅ Couleur badge réponse
 	- ✅ Les articles sont encore necessaires dans les réponses
 	- ✅ Lien de connexion
 	- ✅ Bad words dans le mot
 	- ✅ teams js sort error : https://github.com/mchev/blinest/issues/15
 	- ✅ changer le nom et la photo de la team
 	- ✅ afficher le nombre d'extraits total sur la room
 	- ✅ ban public moderator every room
 	- ✅ suppression message ne fonctionne pas
 	- ✅ Validation du nom des rooms (bad words et reserved words)
 	- ✅ Modification photos de room en fonction du score

- v2.0
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