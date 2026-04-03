<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public static function bootBelongsToUser(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('user_id') && auth()->check()) {
                $model->setAttribute('user_id', auth()->id());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwnedBy(Builder $query, User|int|null $user = null): Builder
    {
        $userId = $user instanceof User ? $user->getKey() : $user;
        
        if (! $userId && auth()->check()) {
            $userId = session('impersonated_user_id') ?: auth()->id();
        }

        return $query->where('user_id', $userId);
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        $query->where($field, $value);

        if (auth()->check()) {
            $userId = session('impersonated_user_id') ?: auth()->id();
            $query->where('user_id', $userId);
        }

        return $query;
    }
}
