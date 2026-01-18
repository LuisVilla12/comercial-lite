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
            'username'=>'luis',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('qazqazqaz9'),
            'tipo'=>'2'
        ]);
         User::create([
            'name' => 'Alberto Campuzano',
            'username'=>'ORIZABA',
            'email' => 'ORIZABA@gmail.com',
            'password' => Hash::make('Sucursal21'),
            'tipo'=>'1'
        ]);
    }
}
