<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        return Inertia::render('Teams/Index');
    }

    public function invitation()
    {
    }
}
