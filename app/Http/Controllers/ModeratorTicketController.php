<?php

namespace App\Http\Controllers;

use App\Game;
use App\ModeratorTicket;
use Illuminate\Http\Request;

class ModeratorTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Game $game, Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $moderatorTicket = new ModeratorTicket([
            'user_id' => auth()->user()->id,
            'game_id' => $game->id,
            'message' => $request->get('message')
        ]);

        $moderatorTicket->save();

        return response()->json($moderatorTicket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ModeratorTicket  $moderatorTicket
     * @return \Illuminate\Http\Response
     */
    public function show(ModeratorTicket $moderatorTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ModeratorTicket  $moderatorTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(ModeratorTicket $moderatorTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ModeratorTicket  $moderatorTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModeratorTicket $moderatorTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ModeratorTicket  $moderatorTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModeratorTicket $moderatorTicket)
    {
        //
    }
}
