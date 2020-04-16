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

        $messages = Message::where('game_id', $request->input('game_id'))->orderby('created_at', 'DESC')->limit(30)->get();

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

        if(auth()->user()) {
            $user = auth()->user();
            $sender_id = $user->id;
            $sender_name = $user->name;
        } else {
            $sender_id = null;
            if ($request->session()->get('guest_name')) {
                $sender_name = $request->session()->get('guest_name');
            } else {
                $sender_name = 'anonyme_' . random_int(100, 999);
                $request->session()->put('guest_name', $sender_name);
            }
        }


        if ($request->input('game_id')) {

            $message = Message::create([
                'sender_id'     => $sender_id,
                'sender_name'   => $sender_name,
                'sender_ip'     => $request->ip(),
                'game_id'       => $request->input('game_id'),
                'message'       => $request->input('message'),
            ]);

            broadcast(new MessageSent($message));

            return response()->json('Nouveau message de ' . $message->sender_name);

        } else {

            return response()->json('Salon non identifé.');

        }
    }
}