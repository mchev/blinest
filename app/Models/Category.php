<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function publicRooms()
    {
        return $this->rooms()->isPublic();
    }

    public function privateRooms()
    {
        return $this->rooms()->isPrivate();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            });
        });
    }

}
