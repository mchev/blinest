<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'game_id',
        'message',
    ];

    protected $with = ['sender', 'receiver'];

    public function scopeBySender($q, $sender)
    {
        $q->where('sender_id', $sender);
    }

    public function scopeByReceiver($q, $receiver)
    {
        $q->where('game_id', $receiver);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->select(['id', 'name']);
    }

    public function receiver()
    {
        return $this->belongsTo(Game::class, 'game_id')->select(['id', 'title']);
    }
}