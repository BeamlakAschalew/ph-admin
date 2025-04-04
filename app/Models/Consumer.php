<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class Consumer extends Model {
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    protected $fillable = [
        'approved',
        'first_name',
        'last_name',
        'primary_phone',
        'secondary_phone',
        'institution_name',
        'subcity_id',
        'woreda',

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

    public function subcity() {
        return $this->belongsTo(Subcity::class);
    }
}
