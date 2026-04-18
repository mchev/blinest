<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Symfony\Component\Console\Output\BufferedOutput;

class DashboardController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
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
        $output = new BufferedOutput;
        Artisan::call('rounds:force-clear', [], $output);

        return back()->withMessage($output->fetch());
    }

    public function clearCache()
    {
        $output = new BufferedOutput;
        Artisan::call('cache:clear', [], $output);

        return back()->withMessage($output->fetch());
    }

    public function regenerateTop10()
    {
        $output = new BufferedOutput;
        Artisan::call('topusers:weekly', [], $output);

        return back()->withMessage($output->fetch());
    }
}
