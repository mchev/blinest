<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldTrack extends Model
{
    protected $connection = 'old';

    protected $table = 'tracks';

    public function game()
    {
        return $this->belongsTo(OldGame::class, 'game_id');
    }
}
