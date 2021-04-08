<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordNotification extends Controller
{
    public function notification()
    {
        return Http::post('https://discord.com/api/webhooks/829720041540550658/9waPMht6NHHke5MfElfYpE7nTmjo5l_R7_e37nQbAEa7KLmPP0cLyiEQF-1oD46E1rqk', [
            'content' => "Test bot blinest!",
            'embeds' => [
                [
                    'title' => "Wow le site peut envoyer des messages à discord!",
                    'description' => "Trop cool!",
                    'color' => '7506394',
                ]
            ],
        ]);

    }
}
