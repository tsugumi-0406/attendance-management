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
            ['work_id' => 1],
            ['work_id' => 3],
            ['work_id' => 5],
            ['work_id' => 7],
            ['work_id' => 9],
        ];

        foreach($unapproved_works as $unapproved_work)
            UnapprovedWork::factory()->state($unapproved_work)->create();
    }
}
