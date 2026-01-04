<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'tracks_history',
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
            'tracks_history' => 'array', // null sera converti en [] lors de l'accès
            'win_streak' => 'integer',
        ];
    }

    /**
     * Accessor pour tracks_history : garantir qu'on retourne toujours un array
     */
    protected function tracksHistory(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? [] : (is_array($value) ? $value : json_decode($value, true) ?? []),
        );
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
