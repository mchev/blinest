<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class CreateContentController extends Controller
{
    public function index()
    {
        return Inertia::render('docs/create-content/Index');
    }
}
