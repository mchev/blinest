<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

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
    ];

    public function messagable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class)
            ->with('team')
            ->select('users.id', 'users.name', 'users.team_id');
    }

    public function getChannelAttribute(): string
    {
        $model = Str::lower(Str::afterLast($this->messagable_type, '\\'));

        return 'chat-'.$model.'.'.$this->messagable_id;
    }

    public function getTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }
}
