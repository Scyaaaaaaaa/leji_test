<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Manager>
 */
class ManagerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            0 => '人資部',
            1 => '管理部',
            2 => '工程部',
            3 => '業務部',
            4 => '資料部'
        ];

        return [
            'name' => $this->faker->name,
            'department' => $departments[rand(0,4)]
        ];
    }
}
