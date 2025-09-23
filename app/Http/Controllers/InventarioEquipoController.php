<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventarioEquipoRequest;
use App\Http\Requests\UpdateInventarioEquipoRequest;
use App\Models\Departamento;
use App\Models\InventarioEquipo;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventarioEquipoImport;
use App\Exports\InventariosExport;
use App\Models\Marca;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarioEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private string $routeName;
    protected string $module = 'inventario';
    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'inventario.';
         $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
         $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
         $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
         $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index(Request $request)
    {
        //$query = InventarioEquipo::query();
        $query = InventarioEquipo::with('departamento'); // Cargar la relación
        
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where('nombre_persona', 'like', '%' . $request->search . '%')
                //->orWhere(column: 'departamento_id', 'like', '%' . $request->search . '%')
                ->orWhere('tipo_pc', 'like', '%' . $request->search . '%')
                ->orWhere('marca_equipo', 'like', '%' . $request->search . '%')
                ->orWhere('sistema_operativo', 'like', '%' . $request->search . '%')

                ->orWhere('procesador', 'like', '%' . $request->search . '%')
                ->orWhere('capacidad_ram', 'like', '%' . $request->search . '%')
                ->orWhere('tipo_ram', 'like', '%' . $request->search . '%')
                ->orWhere('tipo_disco', 'like', '%' . $request->search . '%')
                ->orWhere('capacidad_disco', 'like', '%' . $request->search . '%')
                ->orWhere('user_id', 'like', '%' . $request->search . '%')
                ->orWhereHas('departamento', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->search . '%');
                });
        }

        $inventarios = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Inventario/Index', [
            'titulo' => 'Inventario de Equipos',
            'inventarios' => $inventarios,
            'routeName' => $this->routeName,
            'filters' => $request->only('search'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $departamentos = Departamento::select('id', 'nombre as name')->get();
        $tipos = ['marca_equipo', 'tarjeta_madre', 'camara_web', 'teclado_mouse', 'marca_ram'];

        $marcasPorTipo = [];
        foreach ($tipos as $tipo) {
            $marcasPorTipo[$tipo] = Marca::whereHas('tipos', function ($query) use ($tipo) {
                $query->where('tipo', $tipo);
            })->orderBy('nombre')->get(['id', 'nombre']);
        }
        $usuariosArqueo = User::role( 'Desarrollador')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();



        return Inertia::render('Inventario/Create', [
            'titulo' => 'Inventario de Equipos',
            'routeName' => $this->routeName,
            'departamentos' => $departamentos,
            'marcasPorTipo' => $marcasPorTipo, // 
            'usuariosArqueo' => $usuariosArqueo,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarioEquipoRequest $request)
    {
        //
        InventarioEquipo::create($request->validated());

        return redirect()->route($this->routeName . 'index')->with('success', 'Registro de equipo creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventarioEquipo $inventarioEquipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventarioEquipo $inventario)
    {
        //
        // dd($inventarioEquipo);
        $usuariosArqueo = User::role('Desarrollador') // si tienes campo `role`
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $tipos = ['marca_equipo', 'tarjeta_madre', 'camara_web', 'teclado_mouse', 'marca_ram'];
        $marcasPorTipo = [];
        foreach ($tipos as $tipo) {
            $marcasPorTipo[$tipo] = Marca::whereHas('tipos', function ($query) use ($tipo) {
                $query->where('tipo', $tipo);
            })->orderBy('nombre')->get(['id', 'nombre']);
        }

        $departamentos = Departamento::select('id', 'nombre as name')->get();

        return Inertia::render('Inventario/Edit', [
            'titulo' => 'Editar Equipo',
            'routeName' => $this->routeName,
            'departamentos' => $departamentos,
            'inventario' => $inventario,
            'usuariosArqueo' => $usuariosArqueo,
            'marcasPorTipo' => $marcasPorTipo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventarioEquipoRequest $request, InventarioEquipo $inventario)
    {
        //

        $inventario->update($request->validated());
        //dd($request->validated());
        return redirect()->route($this->routeName . 'index')->with('success', 'Registro de Equipo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventarioEquipo $inventario)
    {
        try {
            $inventario->delete();

            return redirect()
                ->route($this->routeName . 'index')
                ->with('success', 'El equipo fue eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route($this->routeName . 'index')
                ->with('error', 'No se pudo eliminar el equipo. Intenta nuevamente.');
        }
    }

    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new InventarioEquipoImport, $request->file('archivo'));


        return redirect()->back()->with('success', 'Archivo importado correctamente.');
    }
    public function mostrar()
    {
        return Inertia::render('Inventario/Importar');
    }

    public function exportExcel(Request $request) 
    {
        $filters = $request->all(); // Obtener los filtros del request
        return Excel::download(new InventariosExport($filters), 'Inventario_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function generarResponsiva($id)
    {
        $equipo = InventarioEquipo::with('departamento')->findOrFail($id);

        // Ruta absoluta a tu imagen (ajusta esta ruta)
        $logoPath = public_path('/storage/imagenes/pryse_pdf.jpg'); // o storage_path('app/public/logo.jpg')

        // Convertir imagen a Base64
        $logoBase64 = base64_encode(file_get_contents($logoPath));
        $pdf = Pdf::loadView('pdf.responsiva', [
            'equipo' => $equipo,
            'logoBase64' => $logoBase64,
            'fecha' => now()->format('d/m/Y')
        ])->setPaper('a4', 'portrait')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '15mm')
            ->setOption('margin-right', '15mm')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download('responsiva-' . $equipo->nombre_persona . '.pdf');
    }
}
