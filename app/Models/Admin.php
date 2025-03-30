<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Admin extends Authenticatable {
    use HasFactory, Notifiable;


    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'password' => 'hashed',
        ];
    }

    public static function normalizePhoneNumber($phone) {
        $phone = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($phone, '251')) {
            $phone = substr($phone, 3);
        } else if (Str::startsWith($phone, '+251')) {
            $phone = substr($phone, 4);
        } else if (Str::startsWith($phone, '0')) {
            $phone = substr($phone, 1);
        }

        if (!preg_match('/^[79]\d{8}$/', $phone)) {
            return null;
        }

        return $phone;
    }
}
