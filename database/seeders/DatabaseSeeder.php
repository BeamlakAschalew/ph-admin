<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic pharmacy inventory data.
     *
     * Seeding order respects foreign key dependencies:
     *  1. Subcities (no dependencies)
     *  2. Product Units (no dependencies)
     *  3. Admins & Admin Secrets (no dependencies)
     *  4. Consumers (depends on subcities)
     *  5. Suppliers (depends on subcities)
     *  6. Products (depends on product units)
     *  7. Orders (depends on consumers)
     *  8. Order Items & Custom Items (depends on orders & products)
     */
    public function run(): void
    {
        $this->command?->info('🏥 Seeding Pharmacy Inventory Management System...');
        $this->command?->info('');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command?->info('📍 Seeding subcities...');
        $this->call(SubcitySeeder::class);

        $this->command?->info('📦 Seeding product units...');
        $this->call(ProductUnitSeeder::class);

        $this->command?->info('👤 Seeding admins...');
        $this->call(AdminSeeder::class);
        $this->call(AdminSecretSeeder::class);

        $this->command?->info('🏪 Seeding consumers...');
        $this->call(ConsumerSeeder::class);

        $this->command?->info('🚚 Seeding suppliers...');
        $this->call(SupplierSeeder::class);

        $this->command?->info('💊 Seeding pharmaceutical products...');
        $this->call(ProductSeeder::class);

        $this->command?->info('📋 Seeding orders with items...');
        $this->call(OrderSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command?->info('');
        $this->command?->info('✅ Database seeding completed successfully!');
    }
}
