<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;


class DatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departamentos')->insert([
            ['nombre' => 'DIRECCIÓN'],
            ['nombre' => 'ADMINISTRACIÓN'],
            ['nombre' => 'RRHH'],
            ['nombre' => 'ALMACEN'],
            ['nombre' => 'LICITACIONES'],
            ['nombre' => 'CONTABILIDAD'],
            ['nombre' => 'NORMATIVIDAD'],
            ['nombre' => 'OPERACIONES'],
            ['nombre' => 'FISCAL'],
            ['nombre' => 'CUENTAS VARIAS'],
            ['nombre' => 'IMSS'],
            ['nombre' => 'PAGOS'],
            ['nombre' => 'CAPUFE'],
            ['nombre' => 'PROYECTOS'],
            ['nombre' => 'MONITOREO'],
            ['nombre' => 'FLOTILLAS'],
            ['nombre' => 'BIENESTAR'],
            ['nombre' => 'SALUD'],
            ['nombre' => 'FLEX'],
            ['nombre' => 'SISTEMAS'],
            ['nombre' => 'Operaciones y Administradores de Cuenta'],
            ['nombre' => 'Coorporativo de Grupo Pryse'],
            ['nombre' => 'Coorporativo y visitantes de Grupo Pryse'],
            ['nombre' => 'Area de Contabilidad y Coorporativo de Grupo Pryse'],
        ]);

      

    }
}
