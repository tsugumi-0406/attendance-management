<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'テスト',
            'email' => 'abc@gmail.com',
            'password' => Hash::make('password'),
        ];
        DB::table('users')->insert($param);

        User::factory()->count(5)->create();
    }
}
