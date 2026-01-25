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
            ['break_id' => 1],
            ['break_id' => 3],
            ['break_id' => 5],
            ['break_id' => 7],
            ['break_id' => 9],
        ];

        foreach($unapproved_breaks as $unapproved_break)
            UnapprovedBreak::factory()->state($unapproved_break)->create();
    }
}
