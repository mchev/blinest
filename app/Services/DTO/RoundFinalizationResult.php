<?php

namespace App\Services\DTO;

use App\Models\User;

class RoundFinalizationResult
{
    /**
     * @param  list<array{user: User, elo: int}>  $eloUpdates
     */
    public function __construct(
        public bool $processed,
        public array $eloUpdates = [],
        public ?string $skipReason = null,
    ) {}
}
