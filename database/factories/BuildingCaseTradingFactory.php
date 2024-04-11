<?php

namespace Database\Factories;

use App\Models\BuildingCase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuildingCaseTrading>
 */
class BuildingCaseTradingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'case_id' => BuildingCase::all()->random()->id,
            'price' => $this->faker->numberBetween(5000000, 100000000)
        ];
    }
}
