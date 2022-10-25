<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldRoleUser extends Model
{
    protected $connection = 'old';

    protected $table = 'role_user';
}
