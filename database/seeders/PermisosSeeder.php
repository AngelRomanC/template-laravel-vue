<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::create(['name' => 'users.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'users']);
        Permission::create(['name' => 'users.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'users']);
        Permission::create(['name' => 'users.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'users']);
        Permission::create(['name' => 'users.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'users']);       

        Permission::create(['name' => 'module.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'modules']);
        Permission::create(['name' => 'module.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'modules']);
        Permission::create(['name' => 'module.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'modules']);
        Permission::create(['name' => 'module.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'modules']);

        Permission::create(['name' => 'permissions.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'permissions']);
        Permission::create(['name' => 'permissions.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'permissions']);
        Permission::create(['name' => 'permissions.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'permissions']);
        Permission::create(['name' => 'permissions.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'permissions']);        

        Permission::create(['name' => 'roles.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'roles']);
        Permission::create(['name' => 'roles.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'roles']);
        Permission::create(['name' => 'roles.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'roles']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'roles']);




    }
}
