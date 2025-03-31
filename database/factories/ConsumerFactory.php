<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Consumer>
 */
class ConsumerFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'institution_name' => fake()->company(),
            'primary_phone' => $this->generatePhoneNumber(),
            'secondary_phone' => $this->generatePhoneNumber(),
            'license_number' => fake()->unique()->randomNumber(8),
            'subcity_id' => fake()->numberBetween(1, 11),
            'special_place' => fake()->address(),
            'woreda' => fake()->numberBetween(1, 12),
            'approved' => fake()->boolean(),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function generatePhoneNumber(): string {
        $prefix = fake()->randomElement(['9', '7']);
        $number = $prefix . fake()->numerify('########');
        return $number;
    }
}
