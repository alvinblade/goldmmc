<?php

namespace Database\Seeders;

use App\Models\Measure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Measure::query()->create([
            'name' => 'kiloqram'
        ]);

        Measure::query()->create([
            'name' => 'metr'
        ]);

        Measure::query()->create([
            'name' => 'santimetr'
        ]);

        Measure::query()->create([
            'name' => 'hektar'
        ]);

        Measure::query()->create([
            'name' => 'baş'
        ]);

        Measure::query()->create([
            'name' => 'ədəd'
        ]);
    }
}
