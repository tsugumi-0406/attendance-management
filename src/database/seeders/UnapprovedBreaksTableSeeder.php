<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UnapprovedBreak;

class UnapprovedBreaksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unapproved_breaks = [
            [
                'break_id' => 5,
                'user_id' => 2,
                'date' => '2026-02-02',
                'start' => '12:00:00',
                'stop' => '12:40:00',
            ],
            [
                'break_id' => 8,
                'user_id' => 2,
                'date' => '2026-02-02',
                'start' => '15:10:00',
                'stop' => '15:20:00',
            ],
            [
                'break_id' => 3,
                'user_id' => 4,
                'date' => '2026-02-01',
                'start' => '12:10:00',
                'stop' => '13:00:00',
            ],
            [
                'break_id' => 4,
                'user_id' => 4,
                'date' => '2026-02-01',
                'start' => '15:00:00',
                'stop' => '15:10:00',
            ],
            [
                'break_id' => 9,
                'user_id' => 1,
                'date' => '2026-02-03',
                'start' => '12:00:00',
                'stop' => '13:10:00',
            ],
        ];

        foreach($unapproved_breaks as $unapproved_break)
            UnapprovedBreak::factory()->state($unapproved_break)->create();
    }
}
