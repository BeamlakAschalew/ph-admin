<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates pharmaceutical suppliers with realistic
     * Ethiopian business details and contact information.
     */
    public function run(): void
    {
        Supplier::factory()
            ->count(15)
            ->create();

        $this->command?->info('✓ Created '.Supplier::count().' suppliers');
    }
}
