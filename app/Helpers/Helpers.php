<?php

use Illuminate\Support\Facades\Log;

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

if (! function_exists('similarity')) {
    function similarity($a, $b)
    {
        return (levenshtein_utf8(strtolower($a), strtolower($b)));
    }
}

if (! function_exists('translations')) {
    function translations($json)
    {
        if(!file_exists($json)) {
    	   return [];
        }
        return json_decode(file_get_contents($json), true);
    }
}

/*
|--------------------------------------------------------------------------
| utf8_to_extended_ascii
|--------------------------------------------------------------------------
|
| Convert an UTF-8 encoded string to a single-byte string suitable for functions such as levenshtein.
| The function simply uses (and updates) a tailored dynamic encoding (in/out map parameter) 
| where non-ascii characters are remapped to the range [128-255] in order of appearance.
| Thus it supports up to 128 different multibyte code points max over the whole set of strings sharing this encoding.
|
*/
if (! function_exists('utf8_to_extended_ascii')) {
    function utf8_to_extended_ascii($str, &$map)
    {
        // find all multibyte characters (cf. utf-8 encoding specs)
        $matches = array();
        if (!preg_match_all('/[\xC0-\xF7][\x80-\xBF]+/', $str, $matches))
            return $str; // plain ascii string
       
        // update the encoding map with the characters not already met
        foreach ($matches[0] as $mbc)
            if (!isset($map[$mbc]))
                $map[$mbc] = chr(128 + count($map));
       
        // finally remap non-ascii characters
        return strtr($str, $map);
    }
}

if (! function_exists('levenshtein_utf8')) {
    function levenshtein_utf8($s1, $s2)
    {
        $charMap = array();
        $s1 = utf8_to_extended_ascii($s1, $charMap);
        $s2 = utf8_to_extended_ascii($s2, $charMap);
       
        return levenshtein($s1, $s2);
    }
}