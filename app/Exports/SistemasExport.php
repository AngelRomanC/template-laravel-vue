<?php

namespace App\Exports;

use App\Models\Sistema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SistemasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    // Constructor para recibir los filtros
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Sistema::with('departamento', 'usuario');

        // Aplicar filtro si existe
        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('departamento_id', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhereHas('departamento', function ($q2) {
                        $q2->where('nombre', 'like', '%' . $this->filters['search'] . '%');
                    });
            });
        }
        
        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nombre del Sistema',
            'Descripción',
            'Departamento',
            'URL',
            'Fecha Creación Sistema',
            'Fecha Puesta en Producción',
            'Estatus',
            'Número de Usuarios',
            'Servidor',
            'IP Servidor',
            'Sistema Operativo',
            'Servidor BD',
            'IP Servidor BD',
            'Lenguaje Desarrollo',
            'Versión Lenguaje',
            'Usuario que Implemento',
            'Fecha Registro',
            'Fecha Actualización'
        ];
    }

    public function map($sistema): array
    {
        return [
            $sistema->nombre,
            $sistema->descripcion,
            $sistema->departamento->nombre ?? 'No asignado', // Accede a la relación
            $sistema->url,
            $sistema->fecha_creacion ? \Carbon\Carbon::parse($sistema->fecha_creacion)->format('d/m/Y') : '',
            $sistema->fecha_produccion ? \Carbon\Carbon::parse($sistema->fecha_produccion)->format('d/m/Y') : '',
            $sistema->estatus,
            $sistema->numero_usuarios,
            $sistema->nombre_servidor,
            $sistema->ip_servidor,
            $sistema->sistema_operativo,
            $sistema->nombre_servidor_bd,
            $sistema->ip_servidor_bd,
            $sistema->lenguaje_desarrollo,
            $sistema->version_lenguaje,
            $sistema->usuario->name ?? 'No asignado', // Accede al usuario que implementóbloc
            $sistema->created_at->format('d/m/Y H:i'),
            $sistema->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la primera fila (encabezados)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2A629A']]
            ],

            // Estilo para las celdas de fechas
            'F' => ['numberFormat' => ['formatCode' => 'dd/mm/yyyy']],
            'G' => ['numberFormat' => ['formatCode' => 'dd/mm/yyyy']],
            'Q' => ['numberFormat' => ['formatCode' => 'dd/mm/yyyy hh:mm']],
            'R' => ['numberFormat' => ['formatCode' => 'dd/mm/yyyy hh:mm']],
        ];
    }
}