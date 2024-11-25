<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders individuales
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            // TareaSeeder::class,
            // Puedes añadir más seeders aquí si los tienes
        ]);
    }
}
