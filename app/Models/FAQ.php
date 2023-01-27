<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Overtrue\LaravelVote\Traits\Votable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FAQ extends Model
{
    use HasFactory, Votable;

    protected $table = 'faqs';

    protected $appends = [
        'downvotes',
        'upvotes',
    ];

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    public function getUpvotesAttribute()
    {
        return formatVoteNumbers($this->totalUpvotes());
    }

    public function getDownvotesAttribute()
    {
        return formatVoteNumbers($this->totalDownvotes());
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('question', 'like', '%'.$search.'%')
                    ->orWhere('answer', 'like', '%'.$search.'%');
            });
        });
    }
}
