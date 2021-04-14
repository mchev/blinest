# blinest

## Installation Dev

edit the .env file

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

    composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader
    
    php artisan key:generate
    
    php artisan migrate
    
    npm install --only=prod
    
    npm run prod
    
    php artisan config:cache
    
    php artisan route:cache
    

## Daemons

    php artisan websocket:serve
    
    php artisan play
