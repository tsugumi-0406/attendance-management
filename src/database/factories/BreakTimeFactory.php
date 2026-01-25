<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BreakTime;

class BreakTimeFactory extends Factory
{
    protected $model = BreakTime::class;
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
            'start' => $this->faker->time('H:i:s'),
            'stop' => $this->faker->time('H:i:s'),
            'update' => $this->faker->randomElement(['yes', 'no', 'pending']),
            'application_date' => $this->faker->dateTimeBetween('2026-01-09','2026-01-16')->format('Y-m-d'),
        ];
    }
}
