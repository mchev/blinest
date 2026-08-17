<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Seo\DocsHead;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function __construct(private DocsHead $docsHead) {}

    public function index()
    {
        $this->docsHead->applyOverview();

        return Inertia::render('docs/Index');
    }
}
