<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UnapprovedWork;

class UnapprovedWorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unapproved_works = [
            [
                'work_id' => 4,
                'user_id' => 2,
                'date' => '2026-02-02',
                'attendance' => '10:00:00',
                'leaving' => '19:00:00',
            ],
            [
                'work_id' => 1,
                'user_id' => 4,
                'date' => '2026-02-01',
                'attendance' => '9:00:00',
                'leaving' => '18:00:00',
            ],
            [
                'work_id' => 7,
                'user_id' => 1,
                'date' => '2026-02-03',
                'attendance' => '8:30:00',
                'leaving' => '17:30:00',
            ],
        ];

        foreach($unapproved_works as $unapproved_work)
            UnapprovedWork::factory()->state($unapproved_work)->create();
    }
}
