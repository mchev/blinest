<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'total_xp',
        'current_xp',
        'xp_for_next_level',
        'score_public_rooms',
        'rooms_created_count',
        'months_seniority',
        'rounds_played_count',
        'correct_answers_count',
        'tracks_liked_count',
        'messages_count',
        'playlists_created_count',
        'unique_rooms_played_count',
        'best_round_score',
        'consecutive_days_streak',
        'last_login_date',
        'last_calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'last_calculated_at' => 'datetime',
            'last_login_date' => 'date',
            'best_round_score' => 'decimal:1',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
