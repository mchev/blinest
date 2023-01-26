<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/FAQ/Index', [
            'filters' => Request::all('search'),
            'faqs' => FAQ::orderByDesc('updated_at')
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/FAQ/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validated = Request::validate([
            'locale' => ['required', 'max:2'],
            'question' => ['required', 'max:255', 'unique:faqs'],
            'answer' => ['required'],
        ]);

        $faq = FAQ::create($validated);

        return redirect()->route('admin.faqs.edit', $faq)->with('success', __('FAQ created.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FAQ  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(FAQ $faq)
    {
        return Inertia::render('Admin/FAQ/Edit', [
            'faq' => $faq,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FAQ  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(FAQ $faq)
    {

        $validated = Request::validate([
            'locale' => ['required', 'max:2'],
            'question' => ['required', 'max:255', Rule::unique('faqs')->ignore($faq)],
            'answer' => ['required'],
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.edit', $faq)->with('success', __('FAQ updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FAQ  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(FAQ $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', __('FAQ deleted.'));
    }
}
