<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldUser extends Model
{
    protected $connection = 'old';

    protected $table = 'users';

    public function scores()
    {
        return $this->hasMany(OldScore::class, 'user_id');
    }
}
