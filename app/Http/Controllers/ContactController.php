<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
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

        return Inertia::render('Contact/Sent');
    }
}
