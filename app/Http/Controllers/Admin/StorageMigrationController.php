<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Jobs\MigrateFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageMigrationController extends Controller
{
    public function migrate()
    {
        $files = Storage::disk('local')->allFiles();

        foreach ($files as $file) {
            if (! Str::of($file)->contains(['.DS_Store', '.gitignore', '.glide-cache'])) {
                if (Str::startsWith($file, ['public/'])) {
                    $target = Str::replace('public/', '', $file);
                } else {
                    $target = $file;
                }
                MigrateFile::dispatch($file, $target);
            }
        }
    }
}
