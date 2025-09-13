<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'order_number',
        'status',
        'total',
        'reception_date',
        'estimated_delivery_date',
        'actual_delivery_date',
        'notes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'reception_date' => 'date',
        'estimated_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Calculate total based on items
    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
