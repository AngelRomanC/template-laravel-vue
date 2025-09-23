<?php

namespace App\Imports;

use App\Models\Departamento;
use App\Models\InventarioEquipo;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class InventarioEquipoImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1; // encabezado en la primera fila
    }

    public function model(array $row)
    {
        return new InventarioEquipo([
            //'fecha_registro'         => $row['marca_temporal'] ?? now(),
            'fecha_registro'         => $this->parseFecha($row['marca_temporal'] ?? null),
            'nombre_persona'         => $row['nombre'],
            //'departamento_id'        => $row['area'],
            'departamento_id'        => $this->getDepartamentoId($row['area']),
            'tipo_pc'                => $row['tipo_de_pc'],
            'marca_equipo'           => $row['marca_del_equipo'],
            'sistema_operativo'      => $row['sistema_operativo_modelo'],
            'procesador'             => $row['procesador'],
            'tarjeta_madre'          => $row['tarjeta_madre'],
            'tarjeta_grafica'        => $row['tarjeta_grafica'],
            'datos_tarjeta_grafica'  => $row['datos_de_tarjeta_grafica_externa'] ?? 'N/A',
            'tipo_ram'               => $row['tipo_memoria_ram'],
            'capacidad_ram'          => $row['capacidad_memoria_ram'],
            'marca_ram'              => $row['marca_memoria_ram'],
            'tipo_disco'             => $row['tipo_disco_duro'],
            'capacidad_disco'        => $row['capacidad_disco_duro'],
            'teclado_mouse'          => $row['teclado_y_mouse'],
            'camara_web'             => $row['camara_web'],
            'otro_periferico'        => $row['otro_periferico_asignado'],
            //'name_id'                => $row['nombre_del_arqueo'],
            'user_id'                => $this->getUserIdByName($row['nombre_del_arqueo']),
            'observaciones'          => $row['observaciones'] ?? 'N/A',
        ]);
    }



public function parseFecha($valor)
{
    if (!$valor) return now(); // si está vacío, usa la actual

    // Si es número, probablemente es formato serial de Excel
    if (is_numeric($valor)) {
        return Date::excelToDateTimeObject($valor);
    }

    // Si es string: intenta múltiples formatos comunes
    try {
        return Carbon::createFromFormat('d/m/Y H:i:s', $valor); // ej: 2/10/2025 16:50:19
    } catch (\Exception $e) {}

    try {
        return Carbon::createFromFormat('d/m/Y', $valor); // ej: 12/05/2025
    } catch (\Exception $e) {}

    try {
        return Carbon::parse($valor); // fallback (intenta cualquier otro válido)
    } catch (\Exception $e) {}

    return now(); // último recurso
}
private function getDepartamentoId($nombre)
{
    $id = Departamento::where('nombre', $nombre)->value('id');

    if (!$id) {
        // Puedes lanzar una excepción para detener la importación y mostrar el error
        throw new \Exception("El departamento '{$nombre}' no existe en la base de datos.");
    }

    return $id;
}


    private function getUserIdByName($nombre)
    {
        return User::where('name', $nombre)->value('id') ?? null;
    }

}
