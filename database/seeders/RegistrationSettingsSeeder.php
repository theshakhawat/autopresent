<?php

namespace Database\Seeders;

use App\Models\RegistrationSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RegistrationSetting::create([
            'status' => 'inactive',
            'similarity_threshold' => 0.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
