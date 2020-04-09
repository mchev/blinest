<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabVotes extends Model
{
    protected $guarded = [];

    public function lab()
    {
    	return $this->belongsToMany(Lab::class);
    }

}
