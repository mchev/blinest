<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
	protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function rounds()
    {
        return $this->belongsToMany(Round::class, 'round_track');
    }

}
