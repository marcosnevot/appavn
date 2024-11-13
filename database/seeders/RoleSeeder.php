<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear los roles si no existen
        $roles = ['admin', 'employee'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->command->info('Roles seeded: ' . implode(', ', $roles));

        // Asignar roles a los usuarios específicos
        /** 
         *$adminUser = User::where('email', 'avn@admin.com')->first();
         *if ($adminUser) {
         *   $adminUser->assignRole('admin');
         *}

         *$employeeUser = User::where('email', 'nacho@avn.com')->first();
         *if ($employeeUser) {
         *   $employeeUser->assignRole('employee');
         *}

         *$this->command->info('Roles assigned to users');
         */
    }
}
