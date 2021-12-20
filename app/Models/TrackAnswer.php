<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'score',
    ];

    protected $cast = [
        'score' => 'float',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
    
}
