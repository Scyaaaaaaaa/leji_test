<?php

namespace Database\Seeders;

use App\Models\BuildingCase;
use App\Models\BuildingCaseTrading;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manager;

class BuildingCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Manager::factory()->count(20)->create([]);
        BuildingCase::factory()->count(1000)->create([]);
        BuildingCaseTrading::factory()->count(10000)->create();
    }
}
