<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = [
            [
                'name' => 'City General Hospital',
                'address' => '123 Main Street, Downtown',
                'location' => 'New York',
                'phone' => '+1-555-0101',
                'email' => 'info@citygeneral.com',
                'password' => Hash::make('password123'),
                'status' => 'approved',
                'is_active' => true,
            ],
            [
                'name' => 'Metropolitan Medical Center',
                'address' => '456 Oak Avenue, Midtown',
                'location' => 'Los Angeles',
                'phone' => '+1-555-0102',
                'email' => 'contact@metrocenter.com',
                'password' => Hash::make('password123'),
                'status' => 'approved',
                'is_active' => true,
            ],
            [
                'name' => 'Community Health Hospital',
                'address' => '789 Pine Street, Suburbs',
                'location' => 'Chicago',
                'phone' => '+1-555-0103',
                'email' => 'admin@communityhealth.com',
                'password' => Hash::make('password123'),
                'status' => 'approved',
                'is_active' => true,
            ],
            [
                'name' => 'Regional Medical Center',
                'address' => '321 Elm Street, Westside',
                'location' => 'Houston',
                'phone' => '+1-555-0104',
                'email' => 'info@regionalmed.com',
                'password' => Hash::make('password123'),
                'status' => 'pending',
                'is_active' => true,
            ],
            [
                'name' => 'University Hospital',
                'address' => '654 Maple Drive, Campus',
                'location' => 'Boston',
                'phone' => '+1-555-0105',
                'email' => 'contact@universityhospital.com',
                'password' => Hash::make('password123'),
                'status' => 'approved',
                'is_active' => true,
            ],
        ];

        foreach ($hospitals as $hospital) {
            Hospital::create($hospital);
        }
    }
}
