<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthorizationServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\MailServiceProvider;

return [
    AppServiceProvider::class,
    AuthorizationServiceProvider::class,
    HorizonServiceProvider::class,
    MailServiceProvider::class,
];
