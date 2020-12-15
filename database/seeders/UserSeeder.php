<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() === 0) {
            User::create([
                'email' => 'user@example.org',
                'name' => 'User',
                'password' => Hash::make('!QAZ2wsx'),
            ]);
        }
    }
}
