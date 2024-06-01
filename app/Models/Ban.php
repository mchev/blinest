<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Ban extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'comment',
        'expired_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }

    public function setExpiredAtAttribute($value): void
    {
        if (! is_null($value) && ! $value instanceof Carbon) {
            $value = Carbon::parse($value);
        }

        $this->attributes['expired_at'] = $value;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bannable()
    {
        return $this->morphTo();
    }

    public function isPermanent(): bool
    {
        return ! isset($this->attributes['expired_at']) || is_null($this->attributes['expired_at']);
    }

    public function isTemporary(): bool
    {
        return ! $this->isPermanent();
    }

    public function deleteExpiredBans(): void
    {
        $bans = Ban::query()
            ->where('expired_at', '<=', Carbon::now())
            ->delete();
    }
}
