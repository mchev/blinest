<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class HowToController extends Controller
{
    public function index()
    {
        return Inertia::render('docs/howto/Index');
    }
}
