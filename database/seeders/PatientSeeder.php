<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample patients for testing
        $patients = [
            [
                'name' => 'John Doe',
                'phone' => '9876543210',
                'email' => 'john.doe@email.com',
                'password' => Hash::make('password123'),
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'address' => '123 Main Street',
                'city' => 'New York',
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '9876543211',
                'email' => 'jane.smith@email.com',
                'password' => Hash::make('password123'),
                'date_of_birth' => '1985-08-22',
                'gender' => 'female',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'is_active' => true,
            ],
            [
                'name' => 'Michael Johnson',
                'phone' => '9876543212',
                'email' => 'michael.johnson@email.com',
                'password' => Hash::make('password123'),
                'date_of_birth' => '1992-12-03',
                'gender' => 'male',
                'address' => '789 Pine Road',
                'city' => 'Chicago',
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Williams',
                'phone' => '9876543213',
                'email' => 'sarah.williams@email.com',
                'password' => Hash::make('password123'),
                'date_of_birth' => '1988-03-17',
                'gender' => 'female',
                'address' => '321 Elm Street',
                'city' => 'Houston',
                'is_active' => true,
            ],
            [
                'name' => 'David Brown',
                'phone' => '9876543214',
                'email' => 'david.brown@email.com',
                'password' => Hash::make('password123'),
                'date_of_birth' => '1995-07-28',
                'gender' => 'male',
                'address' => '654 Maple Lane',
                'city' => 'Phoenix',
                'is_active' => true,
            ],
        ];

        foreach ($patients as $patientData) {
            Patient::create($patientData);
        }
    }
}
