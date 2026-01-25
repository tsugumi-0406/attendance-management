<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;

class WorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $works = [
            ['user_id' => 1, 'date' => '2026-01-05', 'update' => 'pending'],
            ['user_id' => 2, 'date' => '2026-01-04', 'update' => 'yes'],
            ['user_id' => 3, 'date' => '2026-01-03', 'update' => 'pending'],
            ['user_id' => 4, 'date' => '2026-01-02', 'update' => 'no'],
            ['user_id' => 5, 'date' => '2026-01-01', 'update' => 'pending'],
            ['user_id' => 6, 'date' => '2026-01-05', 'update' => 'yes'],
            ['user_id' => 7, 'date' => '2026-01-04', 'update' => 'pending'],
            ['user_id' => 8, 'date' => '2026-01-03', 'update' => 'no'],
            ['user_id' => 9, 'date' => '2026-01-02', 'update' => 'pending'],
            ['user_id' => 10, 'date' => '2026-01-01', 'update' => 'yes'],
        ];

        foreach($works as $work)
            Work::factory()->state($work)->create();
    }
}
