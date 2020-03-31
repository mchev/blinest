<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $messages = Message::where('game_id', $request->input('game_id'))->where('created_at', '>', Carbon::now()->sub('12 hours'))->orderby('created_at', 'DESC')->limit(20)->get();

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
        $message = Message::create([
            'sender_id'   => $request->input('sender_id'),
            'game_id' => $request->input('game_id'),
            'message'     => $request->input('message'),
        ]);

        broadcast(new MessageSent($message));

        return $message;
    }
}