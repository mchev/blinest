<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'photo_path');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class)->select('id', 'name', 'photo_path');
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }
}
