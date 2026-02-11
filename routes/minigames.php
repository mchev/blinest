<?php

use App\Http\Controllers\Minigames\AlbumCoverController;
use App\Http\Controllers\Minigames\AnagramController;
use App\Http\Controllers\Minigames\FirstLetterController;
use App\Http\Controllers\Minigames\MinigameController;
use App\Http\Controllers\Minigames\QuizController;
use App\Http\Controllers\Minigames\WhoSangController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'logout.banned'])
    ->prefix('minigames')
    ->name('minigames.')
    ->group(function () {
        Route::get('/', [MinigameController::class, 'index'])->name('index');

        // Quiz à 4 choix (solo)
        Route::get('quiz', [QuizController::class, 'play'])->name('quiz.play');
        Route::post('quiz/next', [QuizController::class, 'next'])->name('quiz.next');
        Route::post('quiz/check', [QuizController::class, 'check'])->name('quiz.check');

        // Qui a chanté ?
        Route::get('who-sang', [WhoSangController::class, 'play'])->name('who_sang.play');
        Route::post('who-sang/next', [WhoSangController::class, 'next'])->name('who_sang.next');
        Route::post('who-sang/check', [WhoSangController::class, 'check'])->name('who_sang.check');

        // Anagramme (lettres mélangées, nom d'artiste)
        Route::get('anagram', [AnagramController::class, 'play'])->name('anagram.play');
        Route::post('anagram/next', [AnagramController::class, 'next'])->name('anagram.next');
        Route::post('anagram/check', [AnagramController::class, 'check'])->name('anagram.check');

        // Première lettre
        Route::get('first-letter', [FirstLetterController::class, 'play'])->name('first_letter.play');
        Route::post('first-letter/next', [FirstLetterController::class, 'next'])->name('first_letter.next');
        Route::post('first-letter/check', [FirstLetterController::class, 'check'])->name('first_letter.check');

        // Pochette d'album
        Route::get('album-cover', [AlbumCoverController::class, 'play'])->name('album_cover.play');
        Route::post('album-cover/next', [AlbumCoverController::class, 'next'])->name('album_cover.next');
        Route::post('album-cover/check', [AlbumCoverController::class, 'check'])->name('album_cover.check');
    });
