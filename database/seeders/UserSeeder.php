<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'avn@admin.com',
            'password' => Hash::make('admin'),
        ]);

        User::create([
            'name' => 'Nacho',
            'email' => 'nacho@avn.com',
            'password' => Hash::make('nacho'),
        ]);

        // Añadir más usuarios según sea necesario
    }
}
