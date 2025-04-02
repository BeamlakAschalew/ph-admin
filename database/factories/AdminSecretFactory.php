<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminSecret>
 */
class AdminSecretFactory extends Factory {
    protected static ?string $secret;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'secret' => static::$secret ??= Hash::make('900573972'),
        ];
    }
}
