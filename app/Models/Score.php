<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'photo_path');
    }

    public function team()
    {
        return $this->belongsTo(Team::class)->select('id', 'name', 'photo_path');
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }
}
