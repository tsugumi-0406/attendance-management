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
            [
                'user_id' => 4,
                'date' => '2026-02-01',
                'attendance' => '8:00:00',
                'leaving' => '17:00:00',
                'update' => 'pending',
            ],
            [
                'user_id' => 1,
                'date' => '2026-02-01',
                'attendance' => '9:00:00',
                'leaving' => '18:00:00',
                'update' => 'yes',
            ],
            [
                'user_id' => 3,
                'date' => '2026-02-01',
                'attendance' => '9:00:00',
                'leaving' => null,
                'update' => 'no',
            ],
            [
                'user_id' => 2,
                'date' => '2026-02-02',
                'attendance' => '8:00:00',
                'leaving' => '17:00:00',
                'update' => 'pending',
            ],
            [
                'user_id' => 3,
                'date' => '2026-02-02',
                'attendance' => '8:00:00',
                'leaving' => null,
                'update' => 'yes',
            ],
            [
                'user_id' => 5,
                'date' => '2026-02-02',
                'attendance' => '9:00:00',
                'leaving' => '18:00:00',
                'update' => 'no',
            ],
            [
                'user_id' => 1,
                'date' => '2026-02-03',
                'attendance' => '8:00:00',
                'leaving' => '17:00:00',
                'update' => 'pending',
            ],
            [
                'user_id' => 5,
                'date' => '2026-02-03',
                'attendance' => '8:00:00',
                'leaving' => '17:00:00',
                'update' => 'yes',
            ],
            [
                'user_id' => 2,
                'date' => '2026-02-03',
                'attendance' => '9:00:00',
                'leaving' => '18:00:00',
                'update' => 'no',
            ],
            [
                'user_id' => 4,
                'date' => '2026-02-03',
                'attendance' => '9:00:00',
                'leaving' => '18:00:00',
                'update' => 'yes',
            ],
        ];

        foreach($works as $work)
            Work::factory()->state($work)->create();
    }
}
