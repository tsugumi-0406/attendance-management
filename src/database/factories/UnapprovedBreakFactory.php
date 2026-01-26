<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\UnapprovedBreak;

class UnapprovedBreakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'break_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 10),
            'date' => $this->faker->dateTimeBetween('2026-01-01','2026-01-05')->format('Y-m-d'),
            'start' => $this->faker->time('H:i:s'),
            'stop' => $this->faker->time('H:i:s'),
        ];
    }
}
