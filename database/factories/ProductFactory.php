<?php

namespace Database\Factories;

use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    private static array $usedNames = [];

    /**
     * Real pharmacy inventory products organized by category.
     * Format: [product_name => appropriate_unit_name]
     */
    private static array $pharmacyProducts = [
        // ===== Analgesics & Pain Relief =====
        'Paracetamol 500mg Tablet' => 'Tablet',
        'Paracetamol 250mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Paracetamol 120mg Suppository' => 'Unit (U)',
        'Ibuprofen 400mg Tablet' => 'Tablet',
        'Ibuprofen 200mg Tablet' => 'Tablet',
        'Diclofenac Sodium 50mg Tablet' => 'Tablet',
        'Diclofenac Gel 1% 30g' => 'Gram (g)',
        'Tramadol 50mg Capsule' => 'Capsule',
        'Tramadol 100mg Injection' => 'Milliliter (mL or ml)',
        'Naproxen 500mg Tablet' => 'Tablet',
        'Aspirin 100mg Tablet' => 'Tablet',
        'Aspirin 300mg Tablet' => 'Tablet',
        'Mefenamic Acid 500mg Tablet' => 'Tablet',
        'Celecoxib 200mg Capsule' => 'Capsule',
        'Piroxicam 20mg Capsule' => 'Capsule',
        'Ketorolac 30mg Injection' => 'Milliliter (mL or ml)',

        // ===== Antibiotics =====
        'Amoxicillin 500mg Capsule' => 'Capsule',
        'Amoxicillin 250mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Amoxicillin 125mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Amoxicillin/Clavulanic Acid 625mg' => 'Tablet',
        'Amoxicillin/Clavulanic Acid 457mg Suspension' => 'Milliliter (mL or ml)',
        'Ciprofloxacin 500mg Tablet' => 'Tablet',
        'Ciprofloxacin 250mg Tablet' => 'Tablet',
        'Ciprofloxacin Eye Drops 0.3%' => 'Milliliter (mL or ml)',
        'Azithromycin 500mg Tablet' => 'Tablet',
        'Azithromycin 200mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Doxycycline 100mg Capsule' => 'Capsule',
        'Metronidazole 400mg Tablet' => 'Tablet',
        'Metronidazole 200mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Metronidazole 500mg IV Infusion' => 'Milliliter (mL or ml)',
        'Ceftriaxone 1g Injection' => 'Gram (g)',
        'Ceftriaxone 250mg Injection' => 'Milligram (mg)',
        'Cefixime 200mg Tablet' => 'Tablet',
        'Cefixime 100mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Cephalexin 500mg Capsule' => 'Capsule',
        'Cephalexin 250mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Clarithromycin 500mg Tablet' => 'Tablet',
        'Erythromycin 500mg Tablet' => 'Tablet',
        'Gentamicin 80mg Injection' => 'Milliliter (mL or ml)',
        'Cloxacillin 500mg Capsule' => 'Capsule',
        'Cloxacillin 250mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Nitrofurantoin 100mg Capsule' => 'Capsule',
        'Vancomycin 500mg Injection' => 'Gram (g)',
        'Levofloxacin 500mg Tablet' => 'Tablet',
        'Levofloxacin 750mg Tablet' => 'Tablet',

        // ===== Cardiovascular =====
        'Amlodipine 5mg Tablet' => 'Tablet',
        'Amlodipine 10mg Tablet' => 'Tablet',
        'Lisinopril 5mg Tablet' => 'Tablet',
        'Lisinopril 10mg Tablet' => 'Tablet',
        'Metoprolol 50mg Tablet' => 'Tablet',
        'Metoprolol 100mg Tablet' => 'Tablet',
        'Atorvastatin 10mg Tablet' => 'Tablet',
        'Atorvastatin 20mg Tablet' => 'Tablet',
        'Atorvastatin 40mg Tablet' => 'Tablet',
        'Losartan 50mg Tablet' => 'Tablet',
        'Losartan 100mg Tablet' => 'Tablet',
        'Enalapril 5mg Tablet' => 'Tablet',
        'Enalapril 10mg Tablet' => 'Tablet',
        'Hydrochlorothiazide 25mg Tablet' => 'Tablet',
        'Furosemide 40mg Tablet' => 'Tablet',
        'Furosemide 20mg Injection' => 'Milliliter (mL or ml)',
        'Spironolactone 25mg Tablet' => 'Tablet',
        'Digoxin 0.25mg Tablet' => 'Tablet',
        'Warfarin 5mg Tablet' => 'Tablet',
        'Clopidogrel 75mg Tablet' => 'Tablet',
        'Bisoprolol 5mg Tablet' => 'Tablet',
        'Carvedilol 6.25mg Tablet' => 'Tablet',
        'Nifedipine 30mg Tablet' => 'Tablet',
        'Simvastatin 20mg Tablet' => 'Tablet',

        // ===== Respiratory =====
        'Salbutamol 100mcg Inhaler' => 'Microgram (mcg or µg)',
        'Salbutamol 2mg/5ml Syrup' => 'Milliliter (mL or ml)',
        'Salbutamol 5mg Nebulizer Solution' => 'Milliliter (mL or ml)',
        'Beclomethasone 50mcg Inhaler' => 'Microgram (mcg or µg)',
        'Budesonide 200mcg Inhaler' => 'Microgram (mcg or µg)',
        'Montelukast 10mg Tablet' => 'Tablet',
        'Montelukast 5mg Chewable Tablet' => 'Tablet',
        'Montelukast 4mg Granules' => 'Gram (g)',
        'Cetirizine 10mg Tablet' => 'Tablet',
        'Cetirizine 5mg/5ml Syrup' => 'Milliliter (mL or ml)',
        'Loratadine 10mg Tablet' => 'Tablet',
        'Desloratadine 5mg Tablet' => 'Tablet',
        'Dextromethorphan 15mg/5ml Syrup' => 'Milliliter (mL or ml)',
        'Acetylcysteine 200mg Sachet' => 'Gram (g)',
        'Acetylcysteine 600mg Effervescent' => 'Tablet',
        'Prednisolone 5mg Tablet' => 'Tablet',
        'Prednisolone 15mg/5ml Solution' => 'Milliliter (mL or ml)',
        'Dexamethasone 4mg Injection' => 'Milliliter (mL or ml)',
        'Dexamethasone 0.5mg Tablet' => 'Tablet',

        // ===== Gastrointestinal =====
        'Omeprazole 20mg Capsule' => 'Capsule',
        'Omeprazole 40mg Injection' => 'Milliliter (mL or ml)',
        'Pantoprazole 40mg Tablet' => 'Tablet',
        'Pantoprazole 40mg Injection' => 'Milliliter (mL or ml)',
        'Esomeprazole 40mg Tablet' => 'Tablet',
        'Ranitidine 150mg Tablet' => 'Tablet',
        'Ranitidine 50mg Injection' => 'Milliliter (mL or ml)',
        'Domperidone 10mg Tablet' => 'Tablet',
        'Domperidone 5mg/5ml Suspension' => 'Milliliter (mL or ml)',
        'Metoclopramide 10mg Tablet' => 'Tablet',
        'Metoclopramide 10mg Injection' => 'Milliliter (mL or ml)',
        'Loperamide 2mg Capsule' => 'Capsule',
        'Hyoscine Butylbromide 10mg Tablet' => 'Tablet',
        'Hyoscine Butylbromide 20mg Injection' => 'Milliliter (mL or ml)',
        'Ondansetron 4mg Tablet' => 'Tablet',
        'Ondansetron 8mg Injection' => 'Milliliter (mL or ml)',
        'Bisacodyl 5mg Tablet' => 'Tablet',
        'Lactulose 10g/15ml Solution' => 'Milliliter (mL or ml)',
        'Ursodeoxycholic Acid 300mg Capsule' => 'Capsule',

        // ===== Antidiabetics =====
        'Metformin 500mg Tablet' => 'Tablet',
        'Metformin 850mg Tablet' => 'Tablet',
        'Metformin 1000mg Tablet' => 'Tablet',
        'Glibenclamide 5mg Tablet' => 'Tablet',
        'Gliclazide 80mg Tablet' => 'Tablet',
        'Insulin Regular 100IU/ml' => 'Milliliter (mL or ml)',
        'Insulin NPH 100IU/ml' => 'Milliliter (mL or ml)',
        'Sitagliptin 100mg Tablet' => 'Tablet',
        'Empagliflozin 10mg Tablet' => 'Tablet',
        'Pioglitazone 30mg Tablet' => 'Tablet',

        // ===== CNS & Mental Health =====
        'Diazepam 5mg Tablet' => 'Tablet',
        'Diazepam 10mg Injection' => 'Milliliter (mL or ml)',
        'Lorazepam 2mg Tablet' => 'Tablet',
        'Clonazepam 2mg Tablet' => 'Tablet',
        'Fluoxetine 20mg Capsule' => 'Capsule',
        'Sertraline 50mg Tablet' => 'Tablet',
        'Amitriptyline 25mg Tablet' => 'Tablet',
        'Carbamazepine 200mg Tablet' => 'Tablet',
        'Sodium Valproate 200mg Tablet' => 'Tablet',
        'Sodium Valproate 500mg Tablet' => 'Tablet',
        'Phenytoin 100mg Capsule' => 'Capsule',
        'Phenobarbital 30mg Tablet' => 'Tablet',
        'Haloperidol 5mg Injection' => 'Milliliter (mL or ml)',
        'Haloperidol 5mg Tablet' => 'Tablet',
        'Risperidone 2mg Tablet' => 'Tablet',
        'Olanzapine 10mg Tablet' => 'Tablet',

        // ===== Vitamins & Supplements =====
        'Vitamin C 500mg Tablet' => 'Tablet',
        'Vitamin B Complex Tablet' => 'Tablet',
        'Vitamin B12 1000mcg Injection' => 'Milliliter (mL or ml)',
        'Folic Acid 5mg Tablet' => 'Tablet',
        'Iron Sulfate 200mg Tablet' => 'Tablet',
        'Calcium Carbonate 500mg Tablet' => 'Tablet',
        'Vitamin D3 1000IU Capsule' => 'Capsule',
        'Zinc Sulfate 20mg Tablet' => 'Tablet',
        'Multivitamin Syrup' => 'Milliliter (mL or ml)',
        'Ferrous Sulfate Syrup' => 'Milliliter (mL or ml)',

        // ===== Topical / Dermatological =====
        'Hydrocortisone Cream 1% 15g' => 'Gram (g)',
        'Betamethasone Cream 0.1% 15g' => 'Gram (g)',
        'Clotrimazole Cream 1% 20g' => 'Gram (g)',
        'Miconazole Cream 2% 20g' => 'Gram (g)',
        'Ketoconazole Cream 2% 15g' => 'Gram (g)',
        'Acyclovir Cream 5% 5g' => 'Gram (g)',
        'Silver Sulfadiazine Cream 1% 25g' => 'Gram (g)',
        'Fusidic Acid Cream 2% 15g' => 'Gram (g)',
        'Neomycin/Bacitracin Ointment' => 'Gram (g)',
        'Clobetasol Ointment 0.05% 15g' => 'Gram (g)',

        // ===== Eye & ENT =====
        'Tetracycline Eye Ointment 1%' => 'Gram (g)',
        'Chloramphenicol Eye Drops 0.5%' => 'Milliliter (mL or ml)',
        'Timolol Eye Drops 0.5%' => 'Milliliter (mL or ml)',
        'Latanoprost Eye Drops 0.005%' => 'Milliliter (mL or ml)',
        'Artificial Tears Eye Drops' => 'Milliliter (mL or ml)',
        'Sodium Chloride 0.9% Nasal Drops' => 'Milliliter (mL or ml)',
        'Oxymetazoline Nasal Spray 0.05%' => 'Milliliter (mL or ml)',
        'Fluticasone Nasal Spray 50mcg' => 'Microgram (mcg or µg)',

        // ===== IV Fluids & Electrolytes =====
        'Sodium Chloride 0.9% IV 500ml' => 'Milliliter (mL or ml)',
        'Sodium Chloride 0.9% IV 1000ml' => 'Milliliter (mL or ml)',
        'Ringer\'s Lactate IV 500ml' => 'Milliliter (mL or ml)',
        'Ringer\'s Lactate IV 1000ml' => 'Milliliter (mL or ml)',
        'Dextrose 5% IV 500ml' => 'Milliliter (mL or ml)',
        'Dextrose 5% IV 1000ml' => 'Milliliter (mL or ml)',
        'Potassium Chloride 10% Injection' => 'Milliliter (mL or ml)',
        'Magnesium Sulfate 50% Injection' => 'Milliliter (mL or ml)',
        'Sodium Bicarbonate 8.4% Injection' => 'Milliliter (mL or ml)',
        'Sterile Water for Injection 10ml' => 'Milliliter (mL or ml)',

        // ===== Antihistamines & Allergies =====
        'Chlorpheniramine 4mg Tablet' => 'Tablet',
        'Diphenhydramine 25mg Capsule' => 'Capsule',
        'Diphenhydramine 50mg Injection' => 'Milliliter (mL or ml)',
        'Promethazine 25mg Tablet' => 'Tablet',
        'Promethazine 25mg Injection' => 'Milliliter (mL or ml)',
        'Epinephrine 1mg/ml Injection' => 'Milliliter (mL or ml)',
        'Hydroxyzine 25mg Tablet' => 'Tablet',

        // ===== Antimalarials =====
        'Artemether/Lumefantrine 20/120mg' => 'Tablet',
        'Artemether 80mg Injection' => 'Milliliter (mL or ml)',
        'Chloroquine 150mg Tablet' => 'Tablet',
        'Quinine 300mg Tablet' => 'Tablet',
        'Quinine 600mg Injection' => 'Milliliter (mL or ml)',
        'Primaquine 15mg Tablet' => 'Tablet',
        'Doxycycline 100mg (Malaria Prophylaxis)' => 'Capsule',

        // ===== Anti-TB =====
        'Rifampicin/Isoniazid 150/75mg' => 'Tablet',
        'Rifampicin/Isoniazid/Pyrazinamide/Ethambutol' => 'Tablet',
        'Ethambutol 400mg Tablet' => 'Tablet',
        'Pyrazinamide 500mg Tablet' => 'Tablet',
        'Streptomycin 1g Injection' => 'Gram (g)',

        // ===== Antifungals =====
        'Fluconazole 150mg Capsule' => 'Capsule',
        'Fluconazole 200mg Tablet' => 'Tablet',
        'Fluconazole 2mg/ml IV Infusion' => 'Milliliter (mL or ml)',
        'Ketoconazole 200mg Tablet' => 'Tablet',
        'Nystatin 100000IU Oral Suspension' => 'Milliliter (mL or ml)',
        'Nystatin 100000IU Vaginal Tablet' => 'Tablet',
        'Griseofulvin 500mg Tablet' => 'Tablet',
        'Terbinafine 250mg Tablet' => 'Tablet',

        // ===== Antivirals =====
        'Acyclovir 200mg Tablet' => 'Tablet',
        'Acyclovir 400mg Tablet' => 'Tablet',
        'Acyclovir 250mg Injection' => 'Milligram (mg)',
        'Oseltamivir 75mg Capsule' => 'Capsule',
        'Tenofovir 300mg Tablet' => 'Tablet',
        'Lamivudine 150mg Tablet' => 'Tablet',
        'Zidovudine 300mg Tablet' => 'Tablet',
        'Efavirenz 600mg Tablet' => 'Tablet',
        'Nevirapine 200mg Tablet' => 'Tablet',

        // ===== Contraceptives =====
        'Combined Oral Contraceptive Pill' => 'Tablet',
        'Progestogen-Only Pill' => 'Tablet',
        'Medroxyprogesterone 150mg Injection' => 'Milliliter (mL or ml)',
        'Etonogestrel Implant' => 'Unit (U)',
        'Levonorgestrel 1.5mg Tablet' => 'Tablet',

        // ===== Anesthetics =====
        'Lidocaine 2% Injection' => 'Milliliter (mL or ml)',
        'Lidocaine 5% Ointment' => 'Gram (g)',
        'Bupivacaine 0.5% Injection' => 'Milliliter (mL or ml)',
        'Ketamine 50mg/ml Injection' => 'Milliliter (mL or ml)',
        'Propofol 1% Injection' => 'Milliliter (mL or ml)',

        // ===== Anticoagulants & Hematology =====
        'Enoxaparin 40mg Injection' => 'Milliliter (mL or ml)',
        'Enoxaparin 60mg Injection' => 'Milliliter (mL or ml)',
        'Heparin 5000IU/ml Injection' => 'Milliliter (mL or ml)',
        'Tranexamic Acid 500mg Tablet' => 'Tablet',
        'Tranexamic Acid 500mg Injection' => 'Milliliter (mL or ml)',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get all available products that haven't been used yet
        $available = array_diff_key(self::$pharmacyProducts, self::$usedNames);

        // If all products are used, reset (allows multiple calls)
        if (empty($available)) {
            self::$usedNames = [];
            $available = self::$pharmacyProducts;
        }

        $productName = fake()->randomElement(array_keys($available));
        self::$usedNames[$productName] = true;

        $unitName = self::$pharmacyProducts[$productName];
        $unitId = ProductUnit::where('unit_name', $unitName)->value('id');

        return [
            'product_name' => $productName,
            'product_unit_id' => $unitId ?? fake()->numberBetween(1, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
