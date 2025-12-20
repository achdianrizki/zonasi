<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::truncate();
        $json = File::get('database/data/data_school.json');
        $states = json_decode($json);
        foreach ($states as $state) {
            School::create([
                'name' => $state->name,
                'npsn' => $state->npsn,
                'address' => $state->address,
                'latitude' => $state->latitude,
                'longitude' => $state->longitude,
                'district_id' => 1,
                'village_id' => 1
            ]);
        }
    }
}
