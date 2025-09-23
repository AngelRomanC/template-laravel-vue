<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarcaController extends Controller
{
    private string $routeName;
    protected string $module = 'marcas';
    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'marcas.';
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index2()
    {
        $marcas = Marca::with('tipos')->get();

        return Inertia::render('Marcas/Index', [
            'marcas' => $marcas,
            'routeName' => $this->routeName,

        ]);
    }
    public function index(Request $request)
    {
        $query = Marca::with('tipos');

        // Opcional: búsqueda por nombre si deseas implementar una barra de búsqueda
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $marcas = $query->orderBy('id', 'desc')
            ->paginate(10) // Puedes cambiar el número por el que necesites
            ->withQueryString();

        return Inertia::render('Marcas/Index', [
            //'titulo' => 'Listado de Marcas',
            'marcas' => $marcas,
            'routeName' => $this->routeName,
            'filters' => $request->only('search'), // opcional para mantener el filtro en Vue
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = ['marca_equipo', 'tarjeta_madre', 'camara_web', 'teclado_mouse', 'marca_ram'];

        return Inertia::render('Marcas/Create', [
            'tiposDisponibles' => $tipos,
            'routeName' => $this->routeName,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:marcas,nombre',
            'tipos' => 'required|array|min:1',
            'tipos.*' => 'string|in:marca_equipo,tarjeta_madre,camara_web,teclado_mouse,marca_ram',
        ]);

        $marca = Marca::create(['nombre' => $data['nombre']]);

        foreach ($data['tipos'] as $tipo) {
            $marca->tipos()->create(['tipo' => $tipo]);
        }

        // return redirect()->route('marcas.index')->with('success', 'Marca registrada correctamente');
        return redirect()->route($this->routeName . 'index')->with('success', 'Marca registrada correctamente.');

    }
    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        $tipos = ['marca_equipo', 'tarjeta_madre', 'camara_web', 'teclado_mouse', 'marca_ram'];

        $marca->load('tipos');

        return Inertia::render('Marcas/Edit', [
            'marca' => $marca,
            'tiposPosibles' => $tipos,
            'routeName' => $this->routeName,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'tipos' => 'array',
            'tipos.*' => 'boolean',
        ]);

        $marca->update(['nombre' => $data['nombre']]);

        $tiposSeleccionados = collect($data['tipos'])
            ->filter()
            ->keys()
            ->toArray();

        $marca->tipos()->delete();

        foreach ($tiposSeleccionados as $tipo) {
            $marca->tipos()->create(['tipo' => $tipo]);
        }

        // return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente');
        return redirect()->route($this->routeName . 'index')->with('success', 'Marca actualizada correctamente.');


    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        //return redirect()->route('marcas.index')->with('success', 'Marca eliminada correctamente');
        return redirect()->route($this->routeName . 'index')->with('success', 'Marca eliminada correctamente.');


    }
}
