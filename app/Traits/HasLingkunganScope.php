<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasLingkunganScope
{
    /**
     * A user can be assigned to multiple lingkungan (for lingkungan admins).
     */
    public function lingkungans(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\master_lingkungan::class,
            'user_lingkungans',
            'user_id',
            'lingkungan_id'
        )->withTimestamps();
    }

    /**
     * Get all lingkungan IDs assigned to this user.
     */
    public function getLingkunganIds(): Collection
    {
        return $this->lingkungans()->pluck('master_lingkungans.id');
    }

    /**
     * Check if user has access to a specific lingkungan.
     */
    public function hasLingkunganAccess(int $lingkunganId): bool
    {
        if ($this->isSuperAdmin() || $this->isSnk()) {
            return true;
        }

        return $this->lingkungans()->where('master_lingkungans.id', $lingkunganId)->exists();
    }

    /**
     * Assign a lingkungan to the user.
     */
    public function assignLingkungan(int $lingkunganId): void
    {
        if (!$this->lingkungans()->where('master_lingkungans.id', $lingkunganId)->exists()) {
            $this->lingkungans()->attach($lingkunganId);
        }
    }

    /**
     * Remove a lingkungan from the user.
     */
    public function removeLingkungan(int $lingkunganId): void
    {
        $this->lingkungans()->detach($lingkunganId);
    }

    /**
     * Sync user lingkungans (removes all existing and assigns new ones).
     */
    public function syncLingkungans(array $lingkunganIds): void
    {
        $this->lingkungans()->sync($lingkunganIds);
    }

    /**
     * Scope a query to only include records from user's assigned lingkungans.
     * This should be applied to models that have an id_lingkungan column.
     */
    public function scopeForUserLingkungan(Builder $query, string $lingkunganColumn = 'id_lingkungan'): Builder
    {
        if ($this->isSuperAdmin() || $this->isSnk()) {
            return $query;
        }

        $lingkunganIds = $this->getLingkunganIds();

        if (empty($lingkunganIds)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn($lingkunganColumn, $lingkunganIds);
    }

    /**
     * Apply lingkungan scope to a query builder instance.
     * Usage: $query = User::find(1)->applyLingkunganScope($query);
     */
    public function applyLingkunganScope(Builder $query, string $lingkunganColumn = 'id_lingkungan'): Builder
    {
        if ($this->isSuperAdmin() || $this->isSnk()) {
            return $query;
        }

        $lingkunganIds = $this->getLingkunganIds();

        if (empty($lingkunganIds)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn($lingkunganColumn, $lingkunganIds);
    }

    /**
     * Check if user should see all lingkungans (global scope).
     */
    public function hasGlobalLingkunganAccess(): bool
    {
        return $this->isSuperAdmin() || $this->isSnk();
    }
}
