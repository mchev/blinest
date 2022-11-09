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
        $ban = auth()->user()->bans()->latest()->first();

        return Inertia::render('Pages/Banned', [
            'ban' => [
                'comment' => $ban->comment,
                'expired_at' => $ban->expired_at ? $ban->expired_at->diffForHumans() : 'jamais',
            ],
        ]);
    }
}
