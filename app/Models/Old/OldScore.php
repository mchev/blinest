<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldScore extends Model
{
    protected $connection = 'old';

    protected $table = 'scores';

    public function game()
    {
        return $this->belongsTo(OldGame::class, 'game_id');
    }

    public function user()
    {
        return $this->belongsTo(OldUser::class, 'user_id');
    }
}
