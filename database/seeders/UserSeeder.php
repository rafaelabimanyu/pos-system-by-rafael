<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@tiysapos.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Senja',
            'email'    => 'senja@tiysapos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);

        User::create([
            'name'     => 'Muthia',
            'email'    => 'muthia@tiysapos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);

        User::create([
            'name'     => 'Melani',
            'email'    => 'melani@tiysapos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);

        User::create([
            'name'     => 'Dorkas',
            'email'    => 'dorkas@tiysapos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);

        User::create([
            'name'     => 'Araxsa',
            'email'    => 'araxsa@tiysapos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);
    }
}
