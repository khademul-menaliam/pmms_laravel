<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use BelongsToUser;
    use HasFactory;

    public const STATUSES = [
        'paid' => 'Paid',
        'pending' => 'Pending',
    ];

    public const PAYMENT_OPTIONS = [
        'cash' => 'Cash',
        'bank' => 'Bank',
        'card' => 'Card',
        'mobile_banking' => 'Mobile Banking',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'status',
        'paid_via',
        'paid_to',
        'expense_date',
        'due_date',
        'notes',
        'attachment_path',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
            'due_date' => 'date',
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
            ->when($filters['date_from'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('expense_date', '>=', $value))
            ->when($filters['date_to'] ?? null, fn (Builder $builder, mixed $value) => $builder->whereDate('expense_date', '<=', $value))
            ->when($filters['person'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('paid_to', 'like', '%'.$value.'%'))
            ->when($filters['amount_min'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('amount', '>=', $value))
            ->when($filters['amount_max'] ?? null, fn (Builder $builder, mixed $value) => $builder->where('amount', '<=', $value));
    }
}
