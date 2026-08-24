<?php

namespace App\Models;

use App\Enums\FacebookDataDeletionAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookDataDeletionRequest extends Model
{
    protected $fillable = [
        'confirmation_code',
        'facebook_user_id',
        'user_id',
        'action',
        'source',
        'processed_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'action' => FacebookDataDeletionAction::class,
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isProcessed(): bool
    {
        return $this->action !== FacebookDataDeletionAction::Pending;
    }
}
