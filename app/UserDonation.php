<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDonation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount'
    ];

}
