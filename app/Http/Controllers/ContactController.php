<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ContactMessage;
use App\Seo\LocaleUrl;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class ContactController extends Controller
{
    public function index()
    {
        Head::title(__('Contact'))
            ->description(__('Contact meta description'))
            ->canonical(app(LocaleUrl::class)->canonical('contact'))
            ->alternates(app(LocaleUrl::class)->alternates('contact'));

        return Inertia::render('Contact/Index');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'message' => ['required', 'string'],
        ]);

        $expeditor = User::where('email', config('mail.from.address'))->firstOrFail();

        $expeditor->notify(new ContactMessage(
            $request->user(),
            $validated['email'],
            $validated['message']
        ));

        Head::title(__('Contact'));

        return Inertia::render('Contact/Sent');
    }
}
