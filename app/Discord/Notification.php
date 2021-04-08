<?php

namespace App\Discord;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Notification
{
    public static function send($title, $description, $color)
    {
        if($color === 'danger') {
            $color = '15158332';
        } else if($color === 'success') {
            $color = '3066993';
        } else {
            $color = '3447003';
        }

        return Http::post(env('DISCORD_WEBHOOK_URL'), [
            //'content' => "Test bot blinest!",
            'embeds' => [
                [
                    'title' => $title,
                    'description' => $description,
                    'color' => $color,
                ]
            ],
        ]);

    }
}