<?php

namespace App\Discord;

use App\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Notification
{
    public static function send(Game $game, $description, $color)
    {
        if($color === 'danger') {
            $color = '15158332';
        } else if($color === 'success') {
            $color = '3066993';
        } else {
            $color = '3447003';
        }

        $url = ($game->discord_webhook_url) ? $game->discord_webhook_url : config('services.discord.webhook');

        return Http::post($url, [
            //'content' => "Test bot blinest!",
            'embeds' => [
                [
                    'title' => $game->title,
                    'description' => $description,
                    'color' => $color,
                ]
            ],
        ]);

    }
}