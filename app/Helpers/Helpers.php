<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

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

/**
 * Execute some artisan command in a new console
 */
if (! function_exists('run_background_process')) {
    function run_background_process($command, $data)
    {
        $phpBinaryFinder = new PhpExecutableFinder();
        $phpBinaryPath = $phpBinaryFinder->find();
        // (['php', 'artisan', 'foo:bar', 'json data'])
        $process = new Process ([$phpBinaryPath, base_path('artisan'), $command, $data]); 
        //Run process in background 
        $process->setoptions(['create_new_console' => true]);
        $process->start();
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