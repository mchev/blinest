<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAnswer extends Model
{
    use HasFactory;

    public function track()
    {
        $this->belongsTo(Track::class);
    }
    
}
