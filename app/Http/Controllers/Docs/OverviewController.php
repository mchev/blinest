<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function index()
    {
        return Inertia::render('docs/Index');
    }
}
