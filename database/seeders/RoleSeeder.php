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
        $rol1 = Role::create(['name' => 'Admin']);
        $rol2 = Role::create(['name' => 'Desarrollador']);
        $rol3 = Role::create(['name' => 'Procesos']);
        $rol4 = Role::create(['name' => 'Ejecutivo']);



    }
}
