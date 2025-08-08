<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Modules\Employee\Models\Employee;

/**
 * For testing the employee notification system
 */

class TestEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Create the user first
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name'  => 'Employee',
            'email'      => 'employee@example.com',
        ]);

        // Create the employee record, which will trigger the observer
        Employee::create([
            'user_id' => $user->id,
            'role'    => 'Test Role',
        ]);
    }
}