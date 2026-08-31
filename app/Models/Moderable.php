<?php

namespace App\Models;

use App\Services\Moderation\ModerationModeratorService;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Moderable extends MorphPivot
{
    public $incrementing = true;

    protected $table = 'moderables';

    protected static function booted(): void
    {
        $invalidate = static function (): void {
            app(ModerationModeratorService::class)->invalidateCache();
        };

        static::created($invalidate);
        static::deleted($invalidate);
    }
}
