<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consumer>
 */
class ConsumerFactory extends Factory
{
    protected static ?string $password;

    private static int $phoneCounter = 910000000;

    /**
     * Realistic pharmacy-related institution names.
     */
    private static array $institutions = [
        'St. Paul\'s Hospital Pharmacy',
        'Black Lion Specialty Pharmacy',
        'Yekatit 12 Hospital Pharmacy',
        'Addis Pharmaceutical Supply',
        'Zewditu Memorial Pharmacy',
        'Bete Selam Pharmacy',
        'Ethio Pharma Distributors',
        'Unity Medical Supplies',
        'Ayder Referral Hospital Pharmacy',
        'Hiwot Pharmacy PLC',
        'Gandhi Memorial Pharmacy',
        'Roha Pharmacy & Medical Supply',
        'Hagerselam Pharmacy',
        'Mekelle Pharmaceutical Supply',
        'Amanuel Mental Health Pharmacy',
        'Kebena Pharmacy',
        'Ethio Health Alliance',
        'Pharma Link PLC',
        'Medex Pharmacy',
        'Cure Medical Store',
        'Selam Pharmacy & Cosmetics',
        'Bishoftu Hospital Pharmacy',
        'Debremarkos Pharmacy Center',
        'Nefas Silk Pharmacy',
        'Global Pharma Solutions',
    ];

    /**
     * Realistic special places in Addis Ababa.
     */
    private static array $specialPlaces = [
        'Near Bole Medhanialem Church',
        'Around Merkato, near Commercial Bank',
        'Kazanchis, behind EBC Building',
        '22 Mazoriya, near Total Station',
        'CMC Road, beside St. Michael Church',
        'Gotera, opposite to Awash Bank',
        'Saris, near Abadir Building',
        'Megenagna, around Sheger Park',
        'Ayat, near Summit Mosque',
        'Jemo, behind Jemo Mall',
        'Gofa Camp, near Mela Building',
        'Old Airport, around Wabi Shebelle Hotel',
        'Kera, near Addis Ketema Bus Station',
        'Urael, around Bole Bridge',
        'Gerji, near Mebrat Hail Garage',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $primaryPhone = $this->generateUniquePhone();
        $secondaryPhone = $this->generateUniquePhone();

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'password' => static::$password ??= Hash::make('password'),
            'institution_name' => fake()->randomElement(self::$institutions),
            'primary_phone' => $primaryPhone,
            'secondary_phone' => $secondaryPhone,
            'license_number' => 'LIC-'.str_pad((string) fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'tin_number' => 'TIN-'.str_pad((string) fake()->unique()->numberBetween(1, 999999999), 9, '0', STR_PAD_LEFT),
            'subcity_id' => fake()->numberBetween(1, 11),
            'special_place' => fake()->randomElement(self::$specialPlaces),
            'woreda' => fake()->numberBetween(1, 12),
            'approved' => true,
            'remember_token' => Str::random(10),
            'deleted_at' => null,
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }

    private function generateUniquePhone(): string
    {
        return (string) self::$phoneCounter++;
    }
}
