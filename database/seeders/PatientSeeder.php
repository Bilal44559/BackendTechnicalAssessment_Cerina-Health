<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Generate 20 fake patients
        for ($i = 0; $i < 20; $i++) {
            Patient::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'date_of_birth' => $faker->date('Y-m-d', '2005-01-01')
            ]);
        }
    }
}
