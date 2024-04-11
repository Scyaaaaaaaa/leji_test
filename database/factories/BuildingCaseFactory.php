<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BuildingCaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = [
            0 => '公寓',
            1 => '透天',
            2 => '大樓',
            3 => '別墅',
            4 => '商辦'
        ];

        return [
            'type' => $type[rand(0,4)],
            'name' => $this->faker->company,
            'manager_id' => Manager::all()->random()->id,
        ];
    }
}
