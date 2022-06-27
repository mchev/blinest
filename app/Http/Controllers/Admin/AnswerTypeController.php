<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnswerType;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AnswerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/AnswerTypes/Index', [
            'filters' => Request::all('search'),
            'answer_types' => AnswerType::orderBy('name')
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
        return Inertia::render('Admin/AnswerTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Request::validate([
            'name' => ['required', 'max:50', Rule::unique('answer_types')],
        ]);

        AnswerType::create([
            'name' => Request::get('name'),
            'pronoun' => Request::get('pronoun'),
            'svg_icon' => Request::get('svg_icon'),
        ]);

        return redirect()->route('admin.answer_types.index')->with('success', 'AnswerType created.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnswerType  $answerType
     * @return \Illuminate\Http\Response
     */
    public function edit(AnswerType $answerType)
    {
        return Inertia::render('Admin/AnswerTypes/Edit', [
            'answer_type' => $answerType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnswerType  $answerType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnswerType $answerType)
    {
        $answerType->update(Request::validate([
            'name' => ['required', 'max:50', Rule::unique('answer_types')->ignore($answerType->id)],
            'pronoun' => ['nullable'],
            'svg_icon' => ['nullable'],
        ]));

        return redirect()->back()->with('success', 'AnswerType updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnswerType  $answerType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AnswerType $answerType)
    {
        $answerType->delete();

        return redirect()->route('admin.answer_types.index')->with('success', 'AnswerType deleted.');
    }
}
