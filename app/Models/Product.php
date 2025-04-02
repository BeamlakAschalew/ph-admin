<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model {
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['product_name', 'product_unit_id'];

    public function unit(): HasOne {
        return $this->hasOne(ProductUnit::class, 'id', 'product_unit_id');
    }
}
