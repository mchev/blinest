# blinest

## Installation Dev

edit the .env file

```
composer install
```

```
npm install
```

```
php artisan passport:install
```

```
php artisan migrate
```

```
npm run dev / watch / prod
```

## Installation Prod


edit the .env file

    composer install --no-dev --optimize-autoloader
    
    php artisan migrate



## SERVER STARTUP
    php artisan websocket:serve
    php artisan play
