<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Seo\DocsHead;
use Inertia\Inertia;

class HowToController extends Controller
{
    public function __construct(private DocsHead $docsHead) {}

    public function index()
    {
        $this->docsHead->applyHowTo();

        return Inertia::render('docs/howto/Index');
    }
}
