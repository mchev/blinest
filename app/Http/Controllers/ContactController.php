<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contact/Index');
    }

    public function send()
    {
        $blinest = User::find(1);
        $blinest->notify(new ContactMessage(Auth::user(), Request::input('message')));

        return Inertia::render('Contact/Sent');
    }
}
