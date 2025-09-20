<?php

namespace Database\Seeders;

use App\Models\Vaccine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccines = [
            [
                'name' => 'Pfizer-BioNTech COVID-19 Vaccine',
                'description' => 'mRNA-based vaccine requiring two doses, 21 days apart. 95% efficacy against COVID-19.',
                'status' => 'available',
                'stock_quantity' => 1000,
            ],
            [
                'name' => 'Moderna COVID-19 Vaccine',
                'description' => 'mRNA-based vaccine requiring two doses, 28 days apart. 94.1% efficacy against COVID-19.',
                'status' => 'available',
                'stock_quantity' => 800,
            ],
            [
                'name' => 'Johnson & Johnson COVID-19 Vaccine',
                'description' => 'Single-dose adenovirus vector vaccine. 66.3% efficacy against COVID-19.',
                'status' => 'available',
                'stock_quantity' => 500,
            ],
            [
                'name' => 'AstraZeneca COVID-19 Vaccine',
                'description' => 'Adenovirus vector vaccine requiring two doses. 70% efficacy against COVID-19.',
                'status' => 'available',
                'stock_quantity' => 600,
            ],
            [
                'name' => 'Novavax COVID-19 Vaccine',
                'description' => 'Protein subunit vaccine requiring two doses. 90% efficacy against COVID-19.',
                'status' => 'unavailable',
                'stock_quantity' => 0,
            ],
        ];

        foreach ($vaccines as $vaccine) {
            Vaccine::create($vaccine);
        }
    }
}
