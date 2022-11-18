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
        // Articles
        $articles = [
            'the ',
            'and ',
            '& ',
            'et ',
        ];

        // Remove all between parenthesis
        $string = preg_replace("/\([^)]+\)/", '', $string);

        // Slugify to clean special characters
        $string = Str::slug($string, ' ');

        // Replace text numbers to numeric numbers
        $string = replaceNumbers($string);

        // Remove articles
        return str_replace($articles, '', $string);
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

if (! function_exists('formatVoteNumbers')) {
    function formatVoteNumbers(int $votes)
    {
        return ($votes >= 1000) ? round($votes / 1000, 1).'k' : $votes;
    }
}

if (! function_exists('replaceNumbers')) {
    function replaceNumbers(string $string)
    {
        $find = ['un', 'one', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'fifty', 'soixante', 'soixantedix', 'quatrevingt', 'quatrevingtdix', 'cent', 'mille'];

        $replace = ['1', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '20', '30', '40', '50', '50', '60', '70', '80', '90', '100', '1000'];

        return str_replace($find, $replace, $string);
    }
}
