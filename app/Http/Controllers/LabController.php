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

        if ($request->get('lab_id')) {

            $lab = Lab::find($request->get('lab_id'));

            $request->validate([
                'body'=>'required'
            ]);

            $comment = new Lab([
                'theme' => $lab->theme,
                'type' => $lab->type,
                'body' => $request->get('body'),
                'user_id' => auth()->user()->id,
                'parent_id' => $lab->id
            ]);

            $comment->save();

            return redirect('/lab/' . $lab->id)->with('success', 'La réponse a été enregistrée.');

        } else {

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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lab  $lab
     * @return \Illuminate\Http\Response
     */
    public function show(Lab $lab)
    {
        return view('lab.show', compact('lab'));
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

        $now = date('Y-m-d H:i:s');

        if ($request->get('action') == "plan") {
            $lab->planned_at = $now;
            $lab->opened_at = null;
            $lab->closed_at = null;
            $lab->rejected_at = null;
        }
        if ($request->get('action') == "open") {
            $lab->opened_at = $now;
            $lab->closed_at = null;
            $lab->rejected_at = null;
        }
        if ($request->get('action') == "close") {
            $lab->closed_at = $now;
        }
        if ($request->get('action') == "reject") {
            $lab->rejected_at = $now;
        }

        $lab->update();

        if ($request->get('action') == "delete") {
            $lab->delete();
        }

        return redirect('/lab')->with('success', 'Le ticket a été modifié');

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

        if ($lab->parent_id !== null) {
            return redirect('/lab/' . $lab->parent_id);
        } else {
            return redirect('/lab');
        }
    }

}
