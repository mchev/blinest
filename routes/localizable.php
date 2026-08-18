<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Docs\CreateContentController;
use App\Http\Controllers\Docs\EloController;
use App\Http\Controllers\Docs\GlossaryController;
use App\Http\Controllers\Docs\HowToController;
use App\Http\Controllers\Docs\OverviewController;
use App\Http\Controllers\Docs\SupportController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/**
 * Public, indexable routes registered for each locale surface.
 *
 * @param  non-empty-string  $namePrefix  Route name prefix (e.g. "en.", "es.")
 */
return function (string $namePrefix): void {
    Route::redirect('faq', 'docs/faq', 301)
        ->name($namePrefix.'faq');

    Route::get('docs', [OverviewController::class, 'index'])
        ->name($namePrefix.'docs.index');

    Route::get('docs/level', [LevelController::class, 'index'])
        ->name($namePrefix.'docs.level');

    Route::get('docs/faq', [FAQController::class, 'index'])
        ->name($namePrefix.'docs.faq');

    Route::get('docs/elo', [EloController::class, 'index'])
        ->name($namePrefix.'docs.elo');

    Route::get('docs/glossary', [GlossaryController::class, 'index'])
        ->name($namePrefix.'docs.glossary');

    Route::get('docs/howto', [HowToController::class, 'index'])
        ->name($namePrefix.'docs.howto');

    Route::get('docs/create-content', [CreateContentController::class, 'index'])
        ->name($namePrefix.'docs.create-content');

    Route::get('docs/support', [SupportController::class, 'index'])
        ->name($namePrefix.'docs.support');

    Route::get('pages/{slug}', [PageController::class, 'show'])
        ->name($namePrefix.'pages.show');

    Route::get('contact', [ContactController::class, 'index'])
        ->name($namePrefix.'contact');

    Route::middleware(['auth.banned', 'ip.banned'])->group(function () use ($namePrefix): void {
        Route::get('/', [HomeController::class, 'index'])
            ->name($namePrefix.'home');
    });
};
