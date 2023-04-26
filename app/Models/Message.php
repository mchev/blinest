<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Overtrue\LaravelVote\Traits\Votable;

class Message extends Model
{
    use HasFactory, SoftDeletes, Votable;

    protected $hidden = [
        'messagable_id',
        'messagable_type',
        'user_ip',
        'created_at',
        'updated_at',
    ];

    protected $with = [
        'user',
    ];

    protected $appends = [
        'channel',
        'time',
        'reports',
    ];

    public function messagable()
    {
        return $this->morphTo();
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'messagable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)
            ->with('team')
            ->select('users.id', 'users.name', 'users.team_id', 'users.photo_path');
    }

    public function getChannelAttribute(): string
    {
        $model = Str::lower(Str::afterLast($this->messagable_type, '\\'));

        return 'chat-'.$model.'.'.$this->messagable_id;
    }

    public function getReportsAttribute(): int
    {
        return $this->totalDownvotes();
    }

    public function getTimeAttribute(): string
    {
        if ($this->created_at->format('Y-m-d') >= now()->format('Y-m-d')) {
            return $this->created_at->format('H:i');
        }

        return $this->created_at->diffForHumans();
    }
}
