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
        // Crear el usuario admin y asignarle el rol 'admin'
        $admin = User::create([
            'name' => 'admin',
            'email' => 'avn@admin.com',
            'password' => Hash::make('admin'),
        ]);
        $admin->assignRole('admin');

        // Crear el usuario Nacho y asignarle el rol 'employee'
        $nacho = User::create([
            'name' => 'Nacho',
            'email' => 'nacho@avn.com',
            'password' => Hash::make('nacho'),
        ]);
        $nacho->assignRole('employee');

        $amanda = User::create([
            'name' => 'Amanda',
            'email' => 'amanda@avn.com',
            'password' => Hash::make('amanda'),
        ]);
        $amanda->assignRole('employee');

        $estela = User::create([
            'name' => 'Estela',
            'email' => 'estela@avn.com',
            'password' => Hash::make('estela'),
        ]);
        $estela->assignRole('employee');

        $eva = User::create([
            'name' => 'Eva',
            'email' => 'eva@avn.com',
            'password' => Hash::make('eva'),
        ]);
        $eva->assignRole('employee');

        $gema = User::create([
            'name' => 'Gema',
            'email' => 'gema@avn.com',
            'password' => Hash::make('gema'),
        ]);
        $gema->assignRole('employee');

        $sergio = User::create([
            'name' => 'Sergio',
            'email' => 'sergio@avn.com',
            'password' => Hash::make('sergio'),
        ]);
        $sergio->assignRole('employee');

        // Añadir más usuarios según sea necesario
    }
}
