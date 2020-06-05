<?php

namespace App\Http\Controllers\Moderator;
use App\Http\Controllers\Controller;

use App\Events\MessageSent;
use App\Events\MessageDelete;
use App\Game;
use App\Message;
use App\MessageVote;
use App\ModeratorTicket;
use Illuminate\Http\Request;

class ChatController extends Controller
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

        $messages = Message::where('game_id', 11913)
                            ->withCount('reports')
                            ->having('reports_count', '<', 3)
                            ->orderby('created_at', 'DESC')
                            ->limit(100)
                            ->get();

        return response()->json($messages);
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->input('bot') == true) $sender_name = 'blinest';

        $message = Message::create([
            'sender_id'     => auth()->user()->id,
            'sender_name'   => auth()->user()->name,
            'sender_ip'     => $request->ip(),
            'game_id'       => 11913,
            'message'       => $request->input('message'),
        ]);

        broadcast(new MessageSent($message));

        return response()->json('Nouveau message de ' . $message->sender_name);


    }

}