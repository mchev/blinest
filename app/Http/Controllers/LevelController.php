<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LevelController extends Controller
{
    public function index()
    {
        return Inertia::render('Level/Index');
    }
}
