<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'balance' => 0,
            'address' => 'Admin Address',
            'birthday' => '1990-01-01',
            'account_status' => 'active',
        ]);
        $admin->assignRole('admin');
        $adminToken = $admin->createToken('auth_token')->plainTextToken;
        $this->command->info('Admin Token: ' . $adminToken);

        // User Developer
        $developer = User::create([
            'name' => 'Developer',
            'email' => 'developer@gmail.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'balance' => 0,
            'address' => 'Developer Address',
            'birthday' => '1990-01-01',
            'account_status' => 'active',
        ]);
        $developer->assignRole('developer');
        $developerToken = $developer->createToken('auth_token')->plainTextToken;
        $this->command->info('Developer Token: ' . $developerToken);

        // User Client
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@gmail.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'balance' => 0,
            'address' => 'Client Address',
            'birthday' => '1990-01-01',
            'account_status' => 'active',
        ]);
        $client->assignRole('client');
        $clientToken = $client->createToken('auth_token')->plainTextToken;
        $this->command->info('Client Token: ' . $clientToken);

        // User Freelancer
        $freelancer = User::create([
            'name' => 'Freelancer',
            'email' => 'freelance@gmail.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'balance' => 0,
            'address' => 'Freelancer Address',
            'birthday' => '1990-01-01',
            'account_status' => 'active',
        ]);
        $freelancer->assignRole('freelancer');
        $freelancerToken = $freelancer->createToken('auth_token')->plainTextToken;
        $this->command->info('Freelancer Token: ' . $freelancerToken);
    }
}
