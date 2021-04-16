<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'sender_name',
        'sender_ip',
        'game_id',
        'message',
    ];

    protected $appends = ['sender_profile_picture'];

    public function scopeBySender($q, $sender)
    {
        $q->where('sender_id', $sender);
    }

    public function scopeByReceiver($q, $receiver)
    {
        $q->where('game_id', $receiver);
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id')->select(['id', 'title']);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->select(['id', 'name']);
    }

    public function receiver()
    {
        return $this->belongsTo(Game::class, 'game_id')->select(['id', 'title']);
    }

    public function reports()
    {
        return $this->hasMany(MessageVote::class, 'message_id')->where('type', 'report');
    }


    public function getSenderProfilePictureAttribute()
    {
        if ($this->sender && $this->sender->profile_picture) {
            return $this->sender->profile_picture;
        } else {
            return null;
        }
    }


}