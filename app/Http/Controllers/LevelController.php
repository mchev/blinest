<?php

namespace App\Http\Controllers;

use App\Seo\DocsHead;
use Inertia\Inertia;

class LevelController extends Controller
{
    public function __construct(private DocsHead $docsHead) {}

    public function index()
    {
        $this->docsHead->applyLevel();

        return Inertia::render('docs/level/Index');
    }
}
