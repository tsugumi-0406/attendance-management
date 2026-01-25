<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\UnapprovedWork;

class UnapprovedWorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'work_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 10),
            'date' => $this->faker->dateTimeBetween('2026-01-01','2026-01-05')->format('Y-m-d'),
            'attendance' => $this->faker->time('H:i:s'),
            'leaving' => $this->faker->time('H:i:s'),
            'remarks' => $this->faker->realText(10),
        ];
    }
}
