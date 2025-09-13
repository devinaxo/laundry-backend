<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model {
    protected $fillable = [
        'forename',
        'surname',
        'phone',
        'address',
        'latitude',
        'longitude',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->forename . ' ' . $this->surname;
    }
}
