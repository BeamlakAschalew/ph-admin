<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory {
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => $this->generatePhoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'approved' => fake()->boolean(),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    private function generatePhoneNumber(): string {
        $prefix = fake()->randomElement(['9', '7']);
        $number = $prefix . fake()->numerify('########');
        return $number;
    }
}
