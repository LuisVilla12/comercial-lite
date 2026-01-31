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
            'tipo'=>'1',
            'sucursal_id'=>'1'
        ]);
         User::create([
            'name' => 'Daniel cardenas moreno',
            'username'=>'Ventas ZARAGOZA',
            'email' => 'zaragoza@gmail.com',
            'password' => Hash::make('Sucursal21'),
            'tipo'=>'2',
            'sucursal_id'=>'2'
        ]);
         User::create([
            'name' => 'Angel',
            'username'=>'ORIZABA',
            'email' => 'orizaba@gmail.com',
            'password' => Hash::make('Sucursal21'),
            'tipo'=>'2',
            'sucursal_id'=>'1'
        ]);
        // User::create([
        //     'name' => 'Freddy',
        //     'username'=>'REBSAMEn',
        //     'email' => 'rebsamen@gmail.com',
        //     'password' => Hash::make('Sucursal21'),
        //     'tipo'=>'2',
        //     'sucursal_id'=>'4'
        // ]);
    }
}
