<?php

namespace Database\Seeders;

use App\Models\AdminSecret;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSecretSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        AdminSecret::factory()->create();
    }
}
