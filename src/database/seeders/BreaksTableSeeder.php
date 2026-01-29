<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\BreakTime;

class BreaksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $breaks = [
            [
                'id' => 1,
                'user_id' => 1,
                'date' => '2026-02-01',
                'start' => '12:00:00',
                'stop' => '13:00:00',
                'update' => 'done',
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'date' => '2026-02-01',
                'start' => '12:00:00',
                'stop' => '13:20:00',
                'update' => 'no',
            ],
            [
                'id' => 3,
                'user_id' => 4,
                'date' => '2026-02-01',
                'start' => '12:00:00',
                'stop' => '13:00:00',
                'update' => 'pending',
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'date' => '2026-02-01',
                'start' => '15:00:00',
                'stop' => '15:10:00',
                'update' => 'pending',
            ],
            [
                'id' => 5,
                'user_id' => 2,
                'date' => '2026-02-02',
                'start' => '12:00:00',
                'stop' => '12:40:00',
                'update' => 'pending',
            ],
            [
                'id' => 6,
                'user_id' => 5,
                'date' => '2026-02-02',
                'start' => '12:00:00',
                'stop' => '13:00:00',
                'update' => 'no',
            ],
            [
                'id' => 7,
                'user_id' => 3,
                'date' => '2026-02-02',
                'start' => '12:00:00',
                'stop' => '13:00:00',
                'update' => 'done',
            ],
            [
                'id' => 8,
                'user_id' => 2,
                'date' => '2026-02-02',
                'start' => '15:00:00',
                'stop' => '15:20:00',
                'update' => 'pending',
            ],
            [
                'id' => 9,
                'user_id' => 1,
                'date' => '2026-02-03',
                'start' => '12:00:00',
                'stop' => '13:10:00',
                'update' => 'pending',
            ],
            [
                'id' => 10,
                'user_id' => 2,
                'date' => '2026-02-03',
                'start' => '12:00:00',
                'stop' => '12:50:00',
                'update' => 'no',
            ],
            [
                'id' => 11,
                'user_id' => 4,
                'date' => '2026-02-03',
                'start' => '12:00:00',
                'stop' => '12:40:00',
                'update' => 'done',
            ],
            [
                'id' => 12,
                'user_id' => 5,
                'date' => '2026-02-03',
                'start' => '12:00:00',
                'stop' => '13:10:00',
                'update' => 'done',
            ],
            [
                'id' => 13,
                'user_id' => 2,
                'date' => '2026-02-03',
                'start' => '15:00:00',
                'stop' => '15:20:00',
                'update' => 'no',
            ],
            [
                'id' => 14,
                'user_id' => 4,
                'date' => '2026-02-03',
                'start' => '15:00:00',
                'stop' => '15:20:00',
                'update' => 'done',
            ],
        ];

        foreach($breaks as $break)
            BreakTime::factory()->state($break)->create();
    }
}
