<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->using('App\RoleUser')->withTimestamps();
    }

    public function game()
    {
    	return Game::where('id', $this->pivot->game_id)->first();
    }
}