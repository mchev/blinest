<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoundStanding extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'room_id',
        'user_id',
        'team_id',
        'position',
        'total_score',
        'elo_before',
        'elo_after',
        'elo_change',
        'is_elo_counted',
        'average_response_time',
        'fast_answers_count',
        'total_answers_count',
        'win_streak',
    ];

    protected function casts(): array
    {
        return [
            'total_score' => 'decimal:2',
            'elo_before' => 'integer',
            'elo_after' => 'integer',
            'elo_change' => 'integer',
            'is_elo_counted' => 'boolean',
            'average_response_time' => 'decimal:3',
            'fast_answers_count' => 'integer',
            'total_answers_count' => 'integer',
            'win_streak' => 'integer',
        ];
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
