<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Seo\DocsHead;
use Inertia\Inertia;

class EloController extends Controller
{
    public function __construct(private DocsHead $docsHead) {}

    public function index()
    {
        $this->docsHead->applyElo();

        return Inertia::render('docs/elo/Index');
    }
}
