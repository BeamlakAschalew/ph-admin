<?php

namespace Database\Seeders;

use App\Models\Subcity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcitySeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $staticNames = [
            'Addis Ketema',
            'Akaki Kaliti',
            'Arada',
            'Bole',
            'Gulele',
            'Kirkos',
            'Kolfe Keranio',
            'Lemi Kura',
            'Lideta',
            'Nifas Silk-Lafto',
            'Yeka',
        ];

        foreach ($staticNames as $name) {
            Subcity::create(['name' => $name]);
        }
    }
}
