<?php

namespace App\Http\Traits;

trait Bannable
{
    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    /**
     * If model is banned.
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->getAttributeValue('banned_at') !== null;
    }

    /**
     * If model is not banned.
     *
     * @return bool
     */
    public function isNotBanned(): bool
    {
        return ! $this->isBanned();
    }

    /**
     * Ban model.
     *
     * @param  null|array  $attributes
     */
    public function ban(array $attributes = [])
    {
        return $this->bans()->create($attributes);
    }

    /**
     * Remove ban from model.
     *
     * @return void
     */
    public function unban(): void
    {
        $this->bans()->delete();
    }
}
