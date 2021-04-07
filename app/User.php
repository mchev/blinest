<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
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
        'name', 
        'email', 
        'password', 
        'provider', 
        'provider_id', 
        'banned_until', 
        'last_login_at', 
        'last_login_ip'
    ];

    protected $dates = [
        'banned_until'
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

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id')
                    ->whereHas('game', function($query) {
                        $query->where('public', 1);
                    })
                    ->orderBy('id', 'DESC')
                    ->with('game')
                    ->take(6);
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user')->withTimestamps()->withPivot('game_id');
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
                    ->selectRaw(
                        'game_id, COUNT(*) as total,
                        MAX(updated_at) as latest,
                        MAX(score) as score, 
                        SUM(score) as scores'
                    )
                    ->groupBy('game_id')
                    ->orderBy('scores', 'DESC')
                    ->with('game')
                    ->get();

        return $stats;

    }




}
