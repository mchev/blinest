<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Admin/Categories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categories' => Category::orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->withCount('publicRooms', 'privateRooms')
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
        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        Request::validate([
            'name' => ['required', 'max:50', Rule::unique('categories')],
        ]);

        Category::create([
            'name' => Request::get('name'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', __('Category created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Category $category)
    {
        Head::title($category->name);

        return Inertia::render('Admin/Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'rooms_count' => $category->rooms()->count(),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function update(Request $request, Category $category)
    {
        $category->update(Request::validate([
            'name' => ['required', 'max:50', Rule::unique('categories')->ignore($category->id)],
        ]));

        return redirect()->back()->with('success', __('Category updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('Category deleted'));
    }
}
