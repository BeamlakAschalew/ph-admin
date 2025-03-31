<?php

namespace Database\Seeders;

use App\Models\ProductUnit;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $staticUnits = [
            'Milligram (mg)',
            'Gram (g)',
            'Microgram (mcg or µg)',
            'Milliliter (mL or ml)',
            'Liter (L)',
            'Unit (U)',
            'International Unit (IU)',
            'Teaspoon (tsp)',
            'Tablespoon (tbsp)',
            'Drop (gtt)',
            'Centigram (cg)',
            'Nanogram (ng)',
            'Kilogram (kg)',
            'Fluid Ounce (fl oz)',
            'Pint (pt)',
            'Quart (qt)',
            'Cubic Centimeter (cc)',
            'Molarity (M)',
            'Milliequivalent (mEq)',
            'Percent (% w/v or % v/v)',
        ];

        foreach ($staticUnits as $name) {
            ProductUnit::create(['unit_name' => $name]);
        }
    }
}
