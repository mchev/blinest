<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldGame extends Model
{
    protected $connection = 'old';

    protected $table = 'games';

    public function tracks()
    {
        return $this->hasMany(OldTrack::class, 'game_id');
    }
}
