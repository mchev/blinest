<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Seo\LocaleUrl;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class PageController extends Controller
{
    public function show(string $slug)
    {
        if ($page = Page::where('slug', $slug)->orderByDesc('revised_at')->first()) {
            $path = 'pages/'.$page->slug;
            $localeUrl = app(LocaleUrl::class);

            Head::title($page->title)
                ->canonical($localeUrl->canonical($path))
                ->alternates($localeUrl->alternates($path));

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
            abort(403, config('ban.messages.user'));
        }

        Head::title(__('You have been banned!'))->robots('noindex, nofollow');

        return Inertia::render('Pages/Banned', [
            'ban' => $ban ? [
                'comment' => $ban->comment ?? 'Votre adresse IP a été bloquée',
                'expired_at' => $ban->expired_at ? $ban->expired_at->diffForHumans() : 'jamais',
            ] : null,
        ]);
    }
}
