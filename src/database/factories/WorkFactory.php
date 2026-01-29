<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Work;

class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'date' => $this->faker->dateTimeBetween('2026-01-01','2026-01-05')->format('Y-m-d'),
            'attendance' => $this->faker->time('H:i:s'),
            'leaving' => $this->faker->time('H:i:s'),
            'remarks' => $this->faker->realText(10),
            'update' => $this->faker->randomElement(['done', 'no', 'pending']),
            'application_date' => $this->faker->dateTimeBetween('2026-02-04','2026-02-06')->format('Y-m-d'),
        ];
    }
}