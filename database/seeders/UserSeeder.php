<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Luis Villa',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('qazqazqaz9'),
            'tipo'=>'2'
        ]);
    }
}
