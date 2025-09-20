<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Hospital;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();
        $hospitals = Hospital::where('is_active', true)->get();

        if ($patients->count() > 0 && $hospitals->count() > 0) {
            // Create sample appointments
            for ($i = 0; $i < 20; $i++) {
                $patient = $patients->random();
                $hospital = $hospitals->random();
                $type = rand(0, 1) ? 'covid_test' : 'vaccination';
                $status = ['pending', 'approved', 'completed'][rand(0, 2)];
                
                $appointmentDate = Carbon::now()->addDays(rand(-30, 30))->addHours(rand(8, 17));

                Appointment::create([
                    'patient_id' => $patient->id,
                    'hospital_id' => $hospital->id,
                    'type' => $type,
                    'appointment_date' => $appointmentDate,
                    'status' => $status,
                    'notes' => $this->getRandomNotes($type),
                ]);
            }
        }
    }

    private function getRandomNotes($type)
    {
        if ($type === 'covid_test') {
            $notes = [
                'Patient showing mild symptoms',
                'Contact tracing appointment',
                'Pre-travel testing required',
                'Routine screening',
                'Follow-up test after exposure',
            ];
        } else {
            $notes = [
                'First dose vaccination',
                'Second dose due',
                'Booster shot appointment',
                'Annual vaccination',
                'High-risk patient priority',
            ];
        }

        return $notes[array_rand($notes)];
    }
}
