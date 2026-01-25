<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);

        $this->call(UsersTableSeeder::class);

        $this->call(WorksTableSeeder::class);

        $this->call(BreaksTableSeeder::class);

        $this->call(UnapprovedWorksTableSeeder::class);

        $this->call(UnapprovedBreaksTableSeeder::class);
    }
}
