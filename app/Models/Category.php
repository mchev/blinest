<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (filled($category->slug)) {
                return;
            }

            $category->slug = static::uniqueSlug($category->name);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function landingPath(): string
    {
        return 'blind-test-'.$this->slug;
    }

    public function rooms(): HasMany
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

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'category';
        }

        $slug = $base;
        $suffix = 2;

        while (
            static::query()
                ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
