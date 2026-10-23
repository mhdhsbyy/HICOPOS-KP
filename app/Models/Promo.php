<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'scope',
        'target_id',
        'min_quantity',
        'min_amount',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'target_id')->withTrashed();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'promo_menus')->withTrashed();
    }

    public function target()
    {
        if ($this->scope === 'category') {
            return $this->category();
        }

        return null;
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_promos', 'promo_id', 'transaction_id')
            ->withPivot('discount_amount');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }
}
