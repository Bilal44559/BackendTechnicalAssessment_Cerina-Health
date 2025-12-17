<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Faker\Factory as Faker;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $specializations = ['Cardiology', 'Dermatology', 'Neurology', 'Pediatrics', 'Orthopedics', 'General'];

        // Generate 10 fake doctors
        for ($i = 0; $i < 10; $i++) {
            Doctor::create([
                'name' => $faker->name,
                'specialization' => $faker->randomElement($specializations),
                'availability_schedule' => json_encode([
                    'mon' => '09:00-17:00',
                    'tue' => '09:00-17:00',
                    'wed' => '09:00-17:00',
                    'thu' => '09:00-17:00',
                    'fri' => '09:00-17:00'
                ])
            ]);
        }
    }
}
