<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $diskSpace = exec('df -h');
        } catch (\Exception $e) {
            $diskSpace = 'Unable to retrieve disk space information';
        }

        // Get cache driver and cache size
        $cacheDriver = config('cache.default');

        return Inertia::render('Admin/Dashboard', [
            'diskSpace' => $diskSpace,
            'cacheDriver' => $cacheDriver,
        ]);
    }

    public function forceClearRounds()
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput;
        Artisan::call('rounds:force-clear', [], $output);

        return back()->withMessage($output->fetch());
    }

    public function clearCache()
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput;
        Artisan::call('cache:clear', [], $output);

        return back()->withMessage($output->fetch());
    }

    public function regenerateTop10()
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput;
        Artisan::call('topusers:weekly', [], $output);

        return back()->withMessage($output->fetch());
    }
}
