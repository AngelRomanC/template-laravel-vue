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

        Permission::create(['name' => 'usuarios.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'users']);
        Permission::create(['name' => 'usuarios.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'users']);
        Permission::create(['name' => 'usuarios.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'users']);
        Permission::create(['name' => 'usuarios.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'users']);

        Permission::create(['name' => 'certificacion.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'certifications']);
        Permission::create(['name' => 'certificacion.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'certifications']);
        Permission::create(['name' => 'certificacion.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'certifications']);
        Permission::create(['name' => 'certificacion.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'certifications']);

        Permission::create(['name' => 'departamento.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'departments']);
        Permission::create(['name' => 'departamento.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'departments']);
        Permission::create(['name' => 'departamento.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'departments']);
        Permission::create(['name' => 'departamento.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'departments']);

        Permission::create(['name' => 'inventario.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'inventory']);
        Permission::create(['name' => 'inventario.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'inventory']);
        Permission::create(['name' => 'inventario.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'inventory']);
        Permission::create(['name' => 'inventario.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'inventory']);

        Permission::create(['name' => 'marcas.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'brands']);
        Permission::create(['name' => 'marcas.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'brands']);
        Permission::create(['name' => 'marcas.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'brands']);
        Permission::create(['name' => 'marcas.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'brands']);

        Permission::create(['name' => 'module.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'modules']);
        Permission::create(['name' => 'module.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'modules']);
        Permission::create(['name' => 'module.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'modules']);
        Permission::create(['name' => 'module.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'modules']);

        Permission::create(['name' => 'permiso.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'permissions']);
        Permission::create(['name' => 'permiso.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'permissions']);
        Permission::create(['name' => 'permiso.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'permissions']);
        Permission::create(['name' => 'permiso.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'permissions']);

        Permission::create(['name' => 'proceso.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'processes']);
        Permission::create(['name' => 'proceso.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'processes']);
        Permission::create(['name' => 'proceso.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'processes']);
        Permission::create(['name' => 'proceso.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'processes']);

        Permission::create(['name' => 'roles.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'roles']);
        Permission::create(['name' => 'roles.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'roles']);
        Permission::create(['name' => 'roles.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'roles']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'roles']);

        Permission::create(['name' => 'sistema.index', 'guard_name' => 'web', 'description' => 'Leer Registros', 'module_key' => 'systems']);
        Permission::create(['name' => 'sistema.store', 'guard_name' => 'web', 'description' => 'Crear Registros', 'module_key' => 'systems']);
        Permission::create(['name' => 'sistema.update', 'guard_name' => 'web', 'description' => 'Actualizar Registros', 'module_key' => 'systems']);
        Permission::create(['name' => 'sistema.delete', 'guard_name' => 'web', 'description' => 'Eliminar Registros', 'module_key' => 'systems']);


    }
}
