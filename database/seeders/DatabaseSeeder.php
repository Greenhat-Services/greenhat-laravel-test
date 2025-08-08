<?php

namespace Database\Seeders;

use Modules\User\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Course\Database\Seeders\CourseDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the seeder that creates test employee
        $this->call(TestEmployeeSeeder::class);

        // Calls the seeder that creates the courses and categories
        $this->call(CourseDatabaseSeeder::class);
    }
}