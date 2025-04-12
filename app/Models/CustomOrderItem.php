<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomOrderItem extends Model {
    /** @use HasFactory<\Database\Factories\CustomOrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_name',
        'product_unit_id',
        'quantity',
    ];

    public function unit(): HasOne {
        return $this->hasOne(ProductUnit::class, 'id', 'product_unit_id');
    }
}
