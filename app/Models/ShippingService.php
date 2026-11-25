<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    protected $fillable = ['courier', 'service', 'eta', 'cost', 'is_active'];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}