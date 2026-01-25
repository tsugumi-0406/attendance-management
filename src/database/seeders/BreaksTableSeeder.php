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
            ['update' => 'pending'],
            ['update' => 'yes'],
            ['update' => 'pending'],
            ['update' => 'no'],
            ['update' => 'pending'],
            ['update' => 'yes'],
            ['update' => 'pending'],
            ['update' => 'no'],
            ['update' => 'pending'],
            ['update' => 'yes'],
        ];

        foreach($breaks as $break)
            BreakTime::factory()->state($break)->create();
    }
}
