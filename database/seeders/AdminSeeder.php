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
            'first_name' => 'Admin',
            'last_name'  => 'Beheerder',
            'username' => 'admin',
            'email'    => 'admin@ehb.be',
            'password' => Hash::make('Password!321'),
            'phone'        => '0000000000',
            'street'       => 'Administratief Adres',
            'house_number' => '1',
            'postal_code'  => '0000',
            'city'         => 'Brussel',
            'role'     => 'admin',
            'active'   => true,
        ]);
    }
}
