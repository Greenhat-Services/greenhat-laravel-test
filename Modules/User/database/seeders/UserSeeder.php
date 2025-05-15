<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Sunny',
                'last_name' => 'Bharadwaj',
                'name' => 'Sunny Bharadwaj',
                'email' => 'sunny@example.com',
                'password' => Hash::make('1234'),
            ],
            [
                'first_name' => 'test',
                'last_name' => 'user',
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('1234'),
            ],
        ];

        foreach ($users as $user) {
            $newUser = User::firstOrCreate(
                $user
            );
            $newUser->assign('admin');
        }
    }
}
