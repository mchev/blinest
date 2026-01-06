<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class GlossaryController extends Controller
{
    public function index()
    {
        return Inertia::render('docs/glossary/Index');
    }
}
