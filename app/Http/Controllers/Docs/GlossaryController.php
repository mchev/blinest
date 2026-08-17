<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Seo\DocsHead;
use Inertia\Inertia;

class GlossaryController extends Controller
{
    public function __construct(private DocsHead $docsHead) {}

    public function index()
    {
        $this->docsHead->applyGlossary();

        return Inertia::render('docs/glossary/Index');
    }
}
