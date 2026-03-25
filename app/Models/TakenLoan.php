<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakenLoan extends Model
{
    use BelongsToUser;
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Pending',
        'partial' => 'Partial',
        'paid' => 'Paid',
    ];

    protected $fillable = [
        'user_id',
        'person_name',
        'amount',
        'borrow_date',
        'return_date',
        'reason',
        'status',
        'paid_amount',
        'paid_date',
        'notes',
    ];

    protected $appends = [
        'outstanding_amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'borrow_date' => 'date',
            'return_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    public function getOutstandingAmountAttribute(): float
    {
        return max((float) $this->amount - (float) $this->paid_amount, 0);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['person'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('person_name', 'like', '%'.$value.'%'))
            ->when(
                isset($filters['status']) && array_key_exists($filters['status'], self::STATUSES),
                fn (Builder $builder) => $builder->where('status', $filters['status'])
            )
            ->when($filters['date_from'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('borrow_date', '>=', $value))
            ->when($filters['date_to'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('borrow_date', '<=', $value))
            ->when($filters['amount_min'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('amount', '>=', $value))
            ->when($filters['amount_max'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('amount', '<=', $value));
    }
}
