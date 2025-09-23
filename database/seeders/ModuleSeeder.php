<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Seguridad',
                'key' => 'security',
                'description' => 'Módulo de gestión de seguridad y accesos'
            ],
            [
                'name' => 'Usuarios',
                'key' => 'users',
                'description' => 'Gestión de usuarios del sistema'
            ],
            [
                'name' => 'Roles',
                'key' => 'roles',
                'description' => 'Gestión de roles y permisos'
            ],
            [
                'name' => 'Departamentos',
                'key' => 'departments',
                'description' => 'Gestión de departamentos'
            ],
            [
                'name' => 'Inventario',
                'key' => 'inventory',
                'description' => 'Gestión de inventario de equipos'
            ],
            [
                'name' => 'Sistemas',
                'key' => 'systems',
                'description' => 'Gestión de sistemas y documentación'
            ],
            [
                'name' => 'Procesos',
                'key' => 'processes',
                'description' => 'Gestión de procesos'
            ],
            [
                'name' => 'Certificaciones',
                'key' => 'certifications',
                'description' => 'Gestión de certificaciones'
            ],
            [
                'name' => 'Marcas',
                'key' => 'brands',
                'description' => 'Gestión de marcas de hardware'
            ],
            [
                'name' => 'Modulos',
                'key' => 'modules',
                'description' => 'Gestión de módulos'
            ],
            [
                'name' => 'Permisos',
                'key' => 'permissions',
                'description' => 'Gestión de permisos'
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}