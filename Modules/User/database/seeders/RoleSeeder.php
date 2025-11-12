<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role
        $adminRole = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
        ], [
            'title' => 'Administrator',
        ]);

        // Create admin user if not exists
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@greenhat.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Assign admin role to admin user
        Bouncer::assign($adminRole)->to($adminUser);

        $this->command->info('Admin role and user created successfully!');
        $this->command->info('Email: admin@greenhat.com');
        $this->command->info('Password: password');
    }
}
