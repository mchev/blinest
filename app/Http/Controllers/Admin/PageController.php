<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Admin/Pages/Index', [
            'filters' => Request::all('search', 'trashed'),
            'pages' => Page::orderByDesc('revised_at')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(5)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Admin/Pages/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store()
    {
        Request::validate([
            'title' => ['required', 'max:50', 'unique:pages'],
            'content' => ['required'],
        ]);

        $page = Page::create([
            'title' => Request::get('title'),
            'slug' => Str::slug(Request::get('title')),
            'content' => Request::get('content'),
            'revised_at' => now(),
        ]);

        return redirect()->route('admin.pages.edit', $page)->with('success', __('Page created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Page $page)
    {
        Head::title($page->title);

        return Inertia::render('Admin/Pages/Edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function update(Page $page)
    {
        Request::validate([
            'title' => ['required', 'max:50'],
            'content' => ['required'],
        ]);

        $newPage = Page::create([
            'title' => Request::get('title'),
            'slug' => Str::slug(Request::get('title')),
            'content' => Request::get('content'),
            'original_id' => $page->original_id ?? $page->id,
            'revised_at' => now(),
        ]);

        return redirect()->route('admin.pages.edit', $newPage)->with('success', __('Page updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', __('Page deleted'));
    }
}
