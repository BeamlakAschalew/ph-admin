<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Supplier extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'approved',
        'first_name',
        'last_name',
        'primary_phone',
        'secondary_phone',
        'institution_name',
        'subcity_id',
        'password',
        'license_number',
        'tin_number',
        'special_place',
        'woreda',
        'deleted_at',
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

    public function subcity()
    {
        return $this->belongsTo(Subcity::class);
    }

    public static function normalizePhoneNumber($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($phone, '251')) {
            $phone = substr($phone, 3);
        } elseif (Str::startsWith($phone, '+251')) {
            $phone = substr($phone, 4);
        } elseif (Str::startsWith($phone, '0')) {
            $phone = substr($phone, 1);
        }

        if (! preg_match('/^[79]\d{8}$/', $phone)) {
            return null;
        }

        return $phone;
    }
}
