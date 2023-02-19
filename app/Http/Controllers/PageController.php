<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show(string $slug)
    {
        if ($page = Page::where('slug', $slug)->orderByDesc('revised_at')->first()) {
            return Inertia::render('Pages/Show', [
                'page' => $page,
            ]);
        }

        abort('404');
    }

    public function bannedUser()
    {
        if (auth()->user()) {
            $ban = auth()->user()->bans()->latest()->first();
        } else {
            abort(403, config('ban.message'));
        }

        return Inertia::render('Pages/Banned', [
            'ban' => $ban ? [
                'comment' => $ban->comment ?? 'Votre adresse IP a été bloquée',
                'expired_at' => $ban->expired_at ? $ban->expired_at->diffForHumans() : 'jamais',
            ] : null,
        ]);
    }
}
