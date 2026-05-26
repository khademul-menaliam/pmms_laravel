<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use BelongsToUser;
    use HasFactory;

    public const STATUSES = [
        'paid' => 'Paid',
        'pending' => 'Pending',
    ];

    public const RECEIVED_BY_OPTIONS = [
        'cash' => 'Cash',
        'bank' => 'Bank',
        'mobile_banking' => 'Mobile Banking',
        'card' => 'Card',
    ];

    public const RECURRENCE_OPTIONS = [
        'monthly' => 'Monthly',
        'weekly' => 'Weekly',
        'yearly' => 'Yearly',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'reference',
        'status',
        'received_by',
        'received_from',
        'expected_date',
        'received_date',
        'notes',
        'attachment_path',
        'is_recurring',
        'recurrence_cycle',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expected_date' => 'date',
            'received_date' => 'date',
            'is_recurring' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['category_id'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('category_id', $value))
            ->when(
                isset($filters['status']) && array_key_exists($filters['status'], self::STATUSES),
                fn (Builder $builder) => $builder->where('status', $filters['status'])
            )
            ->when($filters['date_from'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('expected_date', '>=', $value))
            ->when($filters['date_to'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('expected_date', '<=', $value))
            ->when($filters['person'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('received_from', 'like', '%'.$value.'%'))
            ->when($filters['amount_min'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('amount', '>=', $value))
            ->when($filters['amount_max'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('amount', '<=', $value));
    }
}
