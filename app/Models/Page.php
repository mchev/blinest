<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $dates = ['revised_at'];

    protected $appends = ['url', 'date'];

    public function getUrlAttribute()
    {
        return $this->slug ? env('APP_URL') . '/pages/' . $this->slug : null;
    }

    public function getDateAttribute()
    {
        return $this->revised_at->translatedFormat('l j F Y H:i:s');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            });
        });
    }

}
