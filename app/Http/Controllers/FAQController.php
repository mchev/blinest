<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Seo\FaqHead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class FAQController extends Controller
{
    public function __construct(private FaqHead $faqHead) {}

    public function index()
    {
        $faqs = FAQ::withTotalUpvotes()
            ->filter(Request::only('search'))
            ->orderByDesc('total_upvotes')
            ->orderBy('question')
            ->paginate(6);

        $this->faqHead->apply(collect($faqs->items()));

        return Inertia::render('docs/faq/Index', [
            'filters' => Request::all('search'),
            'faqs' => $faqs,
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
