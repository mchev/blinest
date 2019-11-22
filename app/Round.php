<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{

	protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'round_track');
    }

}
