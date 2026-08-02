<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@ehb.be',
            'password' => Hash::make('Password!321'),
            'phone'    => '0000000000',
            'address'  => 'Administratief adres',
            'role'     => 'admin',
            'active'   => true,
        ]);
    }
}
