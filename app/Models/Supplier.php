<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Supplier extends Model {
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory, SoftDeletes;

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
        'special_place',
        'woreda',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function subcity() {
        return $this->belongsTo(Subcity::class);
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
