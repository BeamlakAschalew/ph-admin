<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class Admin extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'phone',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->phone = self::normalizePhoneNumber($user->phone);
        });

        static::updating(function ($user) {
            $user->phone = self::normalizePhoneNumber($user->phone);
        });
    }

    private static function normalizePhoneNumber($phone)
    {
        return $phone;
        $phone = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($phone, '251')) {
            $phone = substr($phone, 3);
        } elseif (Str::startsWith($phone, '+251')) {
            $phone = substr($phone, 4);
        } elseif (Str::startsWith($phone, '0')) {
            $phone = substr($phone, 1);
        }

        return Str::startsWith($phone, ['9', '7']) ? $phone : null;
    }

}
