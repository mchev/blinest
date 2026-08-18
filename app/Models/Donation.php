<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'stripe_checkout_session_id',
        'amount_cents',
        'currency',
        'month_key',
        'user_id',
        'donor_email',
        'donated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'donated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
