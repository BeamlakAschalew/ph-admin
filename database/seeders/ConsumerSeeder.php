<?php

namespace Database\Seeders;

use App\Models\Consumer;
use Illuminate\Database\Seeder;

class ConsumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates pharmacy customers (hospitals, clinics, pharmacies)
     * with realistic Ethiopian names and contact details.
     */
    public function run(): void
    {
        Consumer::factory()->count(30)->create();

        $this->command?->info('✓ Created '.Consumer::count().' consumers');
    }
}
