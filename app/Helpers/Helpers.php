<?php

use Illuminate\Support\Str;

if (! function_exists('pusher')) {
    function pusher()
    {
        return new Pusher\Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options'),
        );
    }
}

if (! function_exists('sanitizeString')) {
    function sanitizeString($string)
    {
        // Remove all between parenthesis
        $string = preg_replace("/\([^)]+\)/", '', $string);

        // Slugify to clean special characters
        $string = Str::slug($string, ' ');

        return $string;
    }
}

if (! function_exists('translations')) {
    function translations($json)
    {
        if (! file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }
}
