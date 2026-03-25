<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use BelongsToUser;
    use HasFactory;

    public const TYPES = [
        'manual' => 'Manual',
        'income' => 'Expected Income',
        'expense' => 'Expense Due',
        'receivable' => 'Collect Money',
        'payable' => 'Return Money',
    ];

    public const CHANNELS = [
        'dashboard' => 'Dashboard Alert',
        'notification' => 'Browser Notification',
        'email' => 'Email Queue Ready',
    ];

    public const STATUSES = [
        'pending' => 'Pending',
        'completed' => 'Completed',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'channel',
        'reminder_date',
        'status',
        'notes',
        'related_resource',
        'related_id',
    ];

    protected function casts(): array
    {
        return [
            'reminder_date' => 'date',
        ];
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                isset($filters['status']) && array_key_exists($filters['status'], self::STATUSES),
                fn (Builder $builder) => $builder->where('status', $filters['status'])
            )
            ->when(
                isset($filters['type']) && array_key_exists($filters['type'], self::TYPES),
                fn (Builder $builder) => $builder->where('type', $filters['type'])
            )
            ->when($filters['date_from'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('reminder_date', '>=', $value))
            ->when($filters['date_to'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('reminder_date', '<=', $value));
    }
}
