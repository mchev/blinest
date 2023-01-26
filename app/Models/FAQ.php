<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('question', 'like', '%'.$search.'%');
            });
        });
    }
}
