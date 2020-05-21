<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageVote extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'message_id', 'type'
    ];

}
