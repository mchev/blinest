<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function donations()
    {
        return $this->hasMany(UserDonation::class, 'user_id')->orderBy('created_at', 'DESC');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'user_id')->orderBy('created_at', 'DESC');
    }

    public function latestScore()
    {
        return $this->hasOne(Score::class, 'user_id')->latest();
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user')->withTimestamps();
    }

    /**
     * The roles that belong to the user.
     */
    public function role()
    {
        return $this->hasMany('App\Role', 'role_user');
    }


    /**
     * Check if the user has a role.
     */
    public function is($roleName)
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->slug == $roleName)
            {
                return true;
            }
        }

        return false;
    }


    /**
     * Check if the user has a role.
     */
    public function isModerator($game)
    {
        foreach ($this->roles()->withPivot('game_id')->get() as $role)
        {
            if ($role->slug == 'moderator' && $game->id == $role->pivot->game_id)
            {
                return true;
            }
        }

        return false;
    }


    public function stats()
    {

        $stats = Score::where('user_id', $this->id)
                    ->selectRaw('game_id, COUNT(*) as total, MAX(score) as score')
                    ->groupBy('game_id')
                    ->orderBy('score', 'DESC')
                    ->with('game')
                    ->get();

        return $stats;

    }




}
