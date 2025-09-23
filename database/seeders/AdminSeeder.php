<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('users')->insert(['name' => 'Admin', 'apellido_paterno' => 'Admin', 'apellido_materno' => 'Admin', 'numero' => '7775420768', 'email' => 'admin@gmail.com', 'email_verified_at' => '2024-01-17 04:50:32', 'password' => Hash::make('Password.1'), 'role' => 'Admin']);
    DB::table('users')->insert(['name' => 'Miguel', 'apellido_paterno' => 'Roman', 'apellido_materno' => 'Chano', 'numero' => '7772052238', 'email' => 'miguel@gmail.com', 'email_verified_at' => '2024-01-17 04:50:32', 'password' => Hash::make('Password.1'), 'role' => 'Desarrollador']);
    DB::table('users')->insert(['name' => 'Moises', 'apellido_paterno' => 'Roman', 'apellido_materno' => 'Chano', 'numero' => '7774449107', 'email' => 'moi@gmail.com', 'email_verified_at' => '2024-01-17 04:50:32', 'password' => Hash::make('Password.1'), 'role' => 'Desarrollador']);
    DB::table('users')->insert(['name' => 'Ricardo', 'apellido_paterno' => 'Roman', 'apellido_materno' => 'Chano', 'numero' => '5540175196', 'email' => 'ricardo@gmail.com', 'email_verified_at' => '2024-01-17 04:50:32', 'password' => Hash::make('Password.1'), 'role' => 'Desarrollador']);

    DB::table('users')->insert(['name' => 'Javier', 'apellido_paterno' => 'Javier', 'apellido_materno' => 'Javier', 'numero' => '7772056987', 'email' => 'javier@gmail.com', 'email_verified_at' => '2024-01-17 04:50:32', 'password' => Hash::make('Password.1'), 'role' => 'Procesos']);
    DB::table('users')->insert(['name' => 'Mario', 'apellido_paterno' => 'Javier', 'apellido_materno' => 'Javier', 'numero' => '7772056987', 'email' => 'mario@gmail.com', 'email_verified_at' => '2024-01-17 04:50:32', 'password' => Hash::make('Password.1'), 'role' => 'Ejecutivo']);

    $user1 = User::where('email', 'admin@gmail.com')->first();
    $user1->assignRole('Admin');

    $user4 = User::where('email', 'miguel@gmail.com')->first();
    $user4->assignRole('Desarrollador');
    $user4 = User::where('email', 'moi@gmail.com')->first();
    $user4->assignRole('Desarrollador');
    $user4 = User::where('email', 'ricardo@gmail.com')->first();
    $user4->assignRole('Desarrollador');

    $user5 = User::where('email', 'javier@gmail.com')->first();
    $user5->assignRole('Procesos');

    $user6 = User::where('email', 'mario@gmail.com')->first();
    $user6->assignRole('Ejecutivo');

    //Aca aignamos las variables para permisos
    $perfil = Role::where('name', 'Admin')->first();
    $Procesos = Role::where('name', operator: 'Procesos')->first();
    $Desarrollador = Role::where('name', operator: 'Desarrollador')->first();
    $Ejecutivo = Role::where('name', operator: 'Ejecutivo')->first();





    //todos los permisos a Usuarios
    $perfil->givePermissionTo(Permission::where('module_key', 'users')->get());
    $Procesos->givePermissionTo(
      Permission::whereIn('module_key', ['processes', 'certifications', 'departments', 'users'])->get()
    );
    $Desarrollador->givePermissionTo(
      Permission::whereIn('module_key', ['brands', 'systems', 'inventory'])->get()
    );
    $Ejecutivo->givePermissionTo(Permission::where('module_key', 'processes')->get());





    //Permisos a Usuario-Sistema

    /*
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documento.index')->first());
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documento.store')->first());
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documento.update')->first());
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documento.delete')->first());

      $usuarioSistema->givePermissionTo(Permission::where('name', 'documentoLegal.index')->first());
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documentoLegal.store')->first());
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documentoLegal.update')->first());
      $usuarioSistema->givePermissionTo(Permission::where('name', 'documentoLegal.delete')->first());
      */


  }
}
