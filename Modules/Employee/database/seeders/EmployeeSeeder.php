<?php

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'rand',
                'last_name' => 'Johnson',
                'name' => 'Rand Johnson',
                'email' => 'rand@example.com',
                'password' => Hash::make('1234'),
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('1234'),
            ],
        ];

        $userOne = User::firstOrCreate(
            $users[0]
        );
        $userOne->employee()->create([
            'role' => 'Family Support Leader',
        ]);

        $userOne->assign('employee');


        $userTwo = User::firstOrCreate(
            $users[1]
        );
        $userTwo->employee()->create([
            'role' => 'Family Support Leader',
        ]);
        $userTwo->assign('employee');
    }
}
