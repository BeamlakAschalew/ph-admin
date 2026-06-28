<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomOrderItem>
 */
class CustomOrderItemFactory extends Factory
{
    /**
     * Non-standard / special-order pharmacy products.
     */
    private static array $customProducts = [
        'Compound Analgesic Cream',
        'Custom Formulation Syrup',
        'Dermatological Compounding Base',
        'Pediatric Oral Suspension (Custom)',
        'Sterile Eye Irrigation Solution',
        'Custom Wound Care Gel',
        'Antiseptic Mouthwash Concentrate',
        'Herbal Cough Mixture',
        'Specialized Pain Relief Patch',
        'Calcium Alginate Dressing',
        'Hydrocolloid Wound Dressing',
        'Custom Blended Vitamin Drops',
        'Oral Rehydration Salts (Custom Flavor)',
        'Antifungal Powder Compound',
        'Burn Relief Ointment (Custom)',
        'Zinc Oxide Paste Compound',
        'Saline Nasal Wash Kit',
        'Medical Grade Petroleum Jelly',
        'Charcoal Activated Suspension',
        'Magnesium Hydroxide Mixture',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitNames = ['Milliliter (mL or ml)', 'Gram (g)', 'Unit (U)'];
        $unitName = $this->faker->randomElement($unitNames);
        $unitId = ProductUnit::where('unit_name', $unitName)->value('id');

        return [
            'order_id' => Order::factory(),
            'product_name' => $this->faker->randomElement(self::$customProducts),
            'product_unit_id' => $unitId ?? $this->faker->numberBetween(1, 20),
            'quantity' => (string) $this->faker->numberBetween(1, 30),
        ];
    }
}
