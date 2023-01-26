<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class FAQController extends Controller
{
    public function index()
    {
        return Inertia::render('FAQ/Index', [
            'filters' => Request::all('search'),
            'faqs' => FAQ::orderByDesc('updated_at')
                ->filter(Request::only('search'))
                ->paginate(30)
        ]);
    }
}
