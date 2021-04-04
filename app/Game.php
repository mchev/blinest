<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Game extends Model
{

	protected $guarded = ['id'];

    protected $with = ['user', 'moderators'];

    public function tracks()
    {
        return $this->hasMany(Track::class, 'game_id');
    }

    public function pendingTickets()
    {
        return $this->hasMany(ModeratorTicket::class, 'game_id')->whereNull('status');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'game_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'game_id');
    }

    public function effects()
    {
        return Effect::all();
    }

    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function moderators()
    {
        return $this->belongsToMany('App\User', 'role_user')->select('users.name', 'users.id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name');
    }

    public function podium()
    {
        return $this->hasMany(Score::class, 'game_id')
            ->groupBy('user_id')
            ->selectRaw( 'user_id, SUM( score ) as score' )
            ->orderBy('score', 'DESC')
            ->with(array('user' => function($query) {
                $query->select('id','name');
            }))
            ->take(10)
            ->get();
    }

    public function podiumMonth()
    {

        $currentMonth = date('m');
        $currentYear = date('Y');

        return $this->hasMany(Score::class, 'game_id')
            ->whereDate('created_at', '>', Carbon::now()->subMonth()->format('Y-m-d'))
            ->groupBy('user_id')
            ->selectRaw( 'user_id, SUM( score ) as score' )
            ->orderBy('score', 'DESC')
            ->with(array('user' => function($query) {
                $query->select('id','name');
            }))
            ->take(10)
            ->get();

    }

    public function podiumWeek()
    {

        $currentMonth = date('m');
        $currentYear = date('Y');

        return $this->hasMany(Score::class, 'game_id')
            ->whereDate('created_at', '>', Carbon::now()->subWeek()->format('Y-m-d'))
            ->groupBy('user_id')
            ->selectRaw( 'user_id, SUM( score ) as score' )
            ->orderBy('score', 'DESC')
            ->with(array('user' => function($query) {
                $query->select('id','name');
            }))
            ->take(10)
            ->get();

    }

}