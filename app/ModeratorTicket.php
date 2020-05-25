<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModeratorTicket extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'track_id', 'game_id', 'message', 'status', 'updated_by'
    ];

    protected $with = [
        'user', 'game'
    ];


    public function user()
    {
        return $this->belongsTo('App\User')->select('id', 'name');
    }


    public function game()
    {
        return $this->belongsTo('App\Game')->select('id', 'title');
    }

}
