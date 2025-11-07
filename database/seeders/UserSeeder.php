<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Example User 1',
                'email' => 'exampleuser1@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Example User 2',
                'email' => 'exampleuser2@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Example User 3',
                'email' => 'exampleuser3@example.com',
                'password' => Hash::make('password123'),
            ],
        ]);
    }
}
