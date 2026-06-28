<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Consumer;
use App\Models\Subcity;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountSeeder extends Seeder
{
    /**
     * Create demo accounts with known credentials for testing.
     *
     * These accounts are pre-approved and ready to use on all login pages.
     */
    public function run(): void
    {
        // --- Admin Demo Account ---
        Admin::create([
            'first_name' => 'Demo',
            'last_name' => 'Admin',
            'phone' => '900000001',
            'password' => Hash::make('password'),
        ])->assignRole('superadmin');

        // --- Consumer Demo Account ---
        Consumer::create([
            'first_name' => 'Demo',
            'last_name' => 'Consumer',
            'primary_phone' => '900000002',
            'secondary_phone' => '900000003',
            'password' => Hash::make('password'),
            'institution_name' => 'Demo Pharmacy PLC',
            'license_number' => 'LIC-DEMO-001',
            'tin_number' => 'TIN-DEMO-001',
            'subcity_id' => Subcity::inRandomOrder()->first()->id ?? 1,
            'special_place' => 'Demo Location, Near Demo Mall',
            'woreda' => 1,
            'approved' => true,
        ]);

        // --- Supplier Demo Account ---
        Supplier::create([
            'first_name' => 'Demo',
            'last_name' => 'Supplier',
            'primary_phone' => '900000004',
            'secondary_phone' => '900000005',
            'password' => Hash::make('password'),
            'institution_name' => 'Demo Pharmaceutical Supply',
            'license_number' => 'SUP-LIC-DEMO-001',
            'tin_number' => 'TIN-DEMO-002',
            'subcity_id' => Subcity::inRandomOrder()->first()->id ?? 1,
            'special_place' => 'Demo Supply Center, Near Demo Hospital',
            'woreda' => 1,
            'approved' => true,
        ]);

        $this->command?->info('✓ Created demo accounts (phone: 90000000[1-5], password: "password")');
    }
}
