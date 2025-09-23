<?php

namespace App\Imports;

use App\Models\Departamento;
use App\Models\InventarioEquipo;
use App\Models\Sistema;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class InformacionSistemaImport implements ToModel, WithHeadingRow {
    public function headingRow(): int {
        return 1; // encabezado en la primera fila
    }

    public function model(array $row) {
        return new Sistema([
            'nombre'                => $row['nombre'],
            'descripcion'           => $row['descripcion'] ?? 'N/A',
            'departamento_id'       => $this->getDepartamentoId($row['departamento_id'] ?? 'ADMINISTRACIÓN'),
            'url'                   => $row['url'] ?? 'N/A',
            'fecha_creacion'        =>  $this->parseFecha($row['fecha_creacion'] ?? '01-01-2025 00:00:00'),
            'fecha_produccion'      =>  $this->parseFecha($row['fecha_produccion'] ?? NOW()),
            'estatus'               => $row['estatus'],
            'numero_usuarios'       => $row['numero_usuarios'],
            'nombre_servidor'       => $row['nombre_servidor'] ?? 'N/A',
            'ip_servidor'           => $row['ip_servidor'] ?? 'N/A',  
            'sistema_operativo'     => $row['sistema_operativo'] ?? 'N/A',
            'nombre_servidor_bd'    => $row['nombre_servidor_bd'] ?? 'N/A',
            'ip_servidor_bd'        => $row['ip_servidor_bd'] ?? 'N/A',
            'lenguaje_desarrollo'   => $row['lenguaje_desarrollo'] ?? 'N/A',
            'version_lenguaje'      => $row['version_lenguaje'] ?? 'N/A',
            'user_id'                => $this->getUserIdByName($row['user_id']),

        ]);
    }



    public function parseFecha($valor) {
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
    private function getDepartamentoId($nombre) {
        $id = Departamento::where('nombre', $nombre)->value('id');

        if (!$id) {
            // Puedes lanzar una excepción para detener la importación y mostrar el error
            throw new \Exception("El departamento '{$nombre}' no existe en la base de datos.");
        }

        return $id;
    }

    private function getUserIdByName($nombre) {
        return User::where('name', $nombre)->value('id') ?? null;
    }
    

}
