<?php

namespace App\Http\Controllers;

use App\Lab;
use App\LabVotes;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $labs = Lab::first();

        return view('lab.index', compact('labs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lab.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'theme'=>'required',
            'type'=>'required',
            'body'=>'required'
        ]);

        $lab = new Lab([
            'theme' => $request->get('theme'),
            'type' => $request->get('type'),
            'body' => $request->get('body'),
            'user_id' => auth()->user()->id,
            'parent_id' => ($request->get('parent_id')) ? $request->get('parent_id') : null
        ]);

        $lab->save();

        return redirect('/lab')->with('success', 'Le ticket a été ajouté');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function show(Lab $lab)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function edit(Lab $lab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lab $lab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lab $lab)
    {
        //
    }

    /**
     * Vote for the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function vote(Request $request, Lab $lab)
    {

        if ( !LabVotes::where('user_id', auth()->user()->id)->where('lab_id', $lab->id)->exists() ) {

            $vote = new LabVotes([
                'lab_id' => $lab->id,
                'user_id' => auth()->user()->id,
                'up' => ($request->get('up')) ? true : null,
                'down' => ($request->get('down')) ? true : null
            ]);

            $vote->save();

        }

        return redirect('/lab');
    }

}
