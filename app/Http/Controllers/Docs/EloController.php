<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class EloController extends Controller
{
    public function index()
    {
        return Inertia::render('docs/elo/Index');
    }
}
