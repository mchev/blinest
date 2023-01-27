<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FAQController extends Controller
{
    public function index()
    {
        return Inertia::render('FAQ/Index', [
            'filters' => Request::all('search'),
            'faqs' => FAQ::orderBy('question')
                ->filter(Request::only('search'))
                ->paginate(6)
        ]);
    }

    public function upvote(FAQ $faq)
    {
        Auth::user()->upvote($faq);
        return redirect()->back();
    }

    public function downvote(FAQ $faq)
    {
        Auth::user()->downvote($faq);
        return redirect()->back();
    }
}
