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
        $expeditor = User::where('email', config('mail.from.address'))->firstOrFail();
        $expeditor->notify(new ContactMessage($request->user(), $request->message));

        return Inertia::render('Contact/Sent');
    }
}
