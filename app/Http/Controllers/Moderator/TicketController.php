<?php

namespace App\Http\Controllers\Moderator;
use App\Http\Controllers\Controller;

use App\Game;
use App\ModeratorTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('moderator');

    }


    public function index()
    {

        //$tickets = ModeratorTicket::where('status', null)->orderBy('created_at', 'ASC')->get();

        $tickets = Game::where('public', 1)
                        ->orderBy('hit', 'DESC')
                        ->whereHas('pendingTickets')
                        ->with('pendingTickets')
                        ->get();

        return response()->json($tickets);
        
    }

    public function close(ModeratorTicket $ticket)
    {

        $ticket->status = 1;
        $ticket->updated_by = auth()->user()->id;
        $ticket->update();

        return response()->json($ticket);
        
    }

}