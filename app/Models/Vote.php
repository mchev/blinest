<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vote extends Model
{
    protected $guarded = [];

    protected $appends = [
        'is_up_vote',
        'is_down_vote',
    ];

    protected $casts = [
        'votes' => 'int',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = \config('vote.votes_table');

        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        self::saving(function ($vote) {
            $userForeignKey = \config('vote.user_foreign_key');
            $vote->{$userForeignKey} = $vote->{$userForeignKey} ?: auth()->id();

            if (\config('vote.uuids')) {
                $vote->{$vote->getKeyName()} = $vote->{$vote->getKeyName()} ?: (string) Str::orderedUuid();
            }
        });
    }

    public function votable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\config('auth.providers.users.model'), \config('vote.user_foreign_key'));
    }

    public function voter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->user();
    }

    public function isUpVote(): bool
    {
        return $this->votes > 0;
    }

    public function isDownVote(): bool
    {
        return $this->votes < 0;
    }

    public function getIsUpVoteAttribute(): bool
    {
        return $this->isUpVote();
    }

    public function getIsDownVoteAttribute(): bool
    {
        return $this->isDownVote();
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('votable_type', app($type)->getMorphClass());
    }

    public function scopeOfVotable(Builder $query, string $type): Builder
    {
        return $this->scopeOfType(...\func_get_args());
    }
}
