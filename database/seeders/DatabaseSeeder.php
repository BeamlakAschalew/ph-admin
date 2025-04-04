<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            SubcitySeeder::class,
            ConsumerSeeder::class,
            AdminSeeder::class,
            ProductUnitSeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            AdminSecretSeeder::class,
            OrderSeeder::class
        ]);
    }
}
