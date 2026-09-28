<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'promo_discount',
        'total',
        'amount_paid',
        'change',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'promo_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCodeAttribute(): string
    {
        $code = 'TRX-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);

        if ($this->relationLoaded('items')) {
            $categoryAbbrevs = $this->items
                ->pluck('menu.category.name')
                ->filter()
                ->map(fn ($categoryName) => strtoupper(substr($categoryName, 0, 2)))
                ->unique()
                ->sort();

            foreach ($categoryAbbrevs as $abbrev) {
                $code .= '-'.$abbrev;
            }
        }

        return $code;
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'transaction_promos', 'transaction_id', 'promo_id')
            ->withPivot('discount_amount');
    }
}
