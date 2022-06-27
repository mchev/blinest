<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer_type_id',
        'value',
        'score',
    ];

    protected $cast = [
        'score' => 'float',
    ];

    protected $with = [
        'type',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function type()
    {
        return $this->belongsTo(AnswerType::class, 'answer_type_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'answer_id');
    }
}
