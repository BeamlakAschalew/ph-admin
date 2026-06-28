<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a comprehensive set of realistic pharmacy inventory products.
     * The ProductFactory contains ~200+ real pharmaceutical products
     * organized by therapeutic category.
     */
    public function run(): void
    {
        // Create all products from the factory's curated medication list
        // The factory tracks used names to avoid duplicates
        $productsToCreate = Product::factory()->count(180);

        // Generate products in chunks to handle the large dataset
        $productsToCreate->create();

        $this->command?->info('✓ Created '.Product::count().' pharmaceutical products');
    }
}
