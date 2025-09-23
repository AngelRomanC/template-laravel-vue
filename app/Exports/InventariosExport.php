<?php

namespace App\Exports;

use App\Models\InventarioEquipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventariosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    // Constructor para recibir los filtros
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = InventarioEquipo::with('departamento', 'usuario');

        // Aplicar filtro si existe
        if (!empty($this->filters['search'])) {
            $query->where(function($q) {
            $q->where('nombre_persona', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('tipo_pc', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('marca_equipo', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('sistema_operativo', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('procesador', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('capacidad_ram', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('tipo_ram', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('tipo_disco', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('capacidad_disco', 'like', '%' . $this->filters['search'] . '%')
                ->orWhere('name_id', 'like', '%' . $this->filters['search'] . '%')
                ->orWhereHas('departamento', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->filters['search'] . '%');
                });
            });
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Registro del Sistema',
            'Nombre de la Persona',
            'Departamento',
            'Puesto',
            'Tipo de Equipo',
            'Marca del Equipo',
            'Sistema Operativo',
            'Procesador',
            'Tarjeta Madre',
            'Tarjeta Gráfica',
            'Datos de la Tarjeta Gráfica',
            'Tipo de RAM',
            'Capacidad de RAM',
            'Marca de RAM',
            'Tipo de Disco Duro',
            'Capacidad de Disco Duro',
            'Teclado y Mouse',
            'Camara Web',
            'Otro Periférico',
            'Software Remoto',
            'ID Remoto',
            'Contraseña Remota',
            'Nombre del Usuario',
            'Observaciones',
            'Fecha de Registro',
            'Fecha Actualización'
        ];
    }

    public function map($inventario): array
    {
        return [
            $inventario->fecha_registro,
            $inventario->nombre_persona,
            $inventario->departamento->nombre ?? 'No asignado', // Accede a la relación
            $inventario->puesto,
            $inventario->tipo_pc,
            $inventario->marca_equipo,
            $inventario->sistema_operativo,
            $inventario->procesador,
            $inventario->tarjeta_madre,
            $inventario->tarjeta_grafica,
            $inventario->datos_tarjeta_grafica,
            $inventario->tipo_ram,
            $inventario->capacidad_ram,
            $inventario->marca_ram,
            $inventario->tipo_disco,
            $inventario->capacidad_disco,
            $inventario->teclado_mouse,
            $inventario->camara_web,
            $inventario->otro_periferico,
            $inventario->software_remoto,
            $inventario->id_remoto,
            $inventario->password_remoto,
            //$inventario->usuario->name ?? 'No asignado', // Accede al usuario
            $inventario->usuario->name . ' ' . 
            $inventario->usuario->apellido_paterno . ' ' . 
            $inventario->usuario->apellido_materno ?? 'No asignado',
            $inventario->observaciones,
            $inventario->created_at->format('d/m/Y H:i'),
            $inventario->updated_at->format('d/m/Y H:i')
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
        ];
    }
}
