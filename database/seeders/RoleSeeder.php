<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol1 = Role::create([
            'name' => 'Admin',
            'description' => 'Acceso completo al sistema, puede gestionar usuarios, roles y todas las funcionalidades'
        ]);

        $rol2 = Role::create([
            'name' => 'Desarrollador',
            'description' => 'Acceso a módulos de desarrollo y configuración, puede gestionar ciertas funcionalidades técnicas'
        ]);

    }
}
