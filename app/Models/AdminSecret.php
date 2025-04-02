<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSecret extends Model {
    /** @use HasFactory<\Database\Factories\AdminSecretFactory> */
    use HasFactory;

    protected $fillable = [
        'secret'
    ];

    protected function casts(): array {
        return [
            'secret' => 'hashed',
        ];
    }
}
