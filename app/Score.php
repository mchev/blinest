<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
	protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

}
