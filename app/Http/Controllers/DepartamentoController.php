<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartamentoController extends Controller
{
    private string $routeName;
    protected string $module = 'departamento';

    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'departamentos.';    
        
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = Departamento::query();

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $departamentos = $query->orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render("Departamento/Index", [
            'titulo' => 'Lista de Departamentos',
            'departamentos' => $departamentos,
            'routeName' => $this->routeName,
            'filters' => $request->only('search'), // ← Esto mantiene el valor en el buscador

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Departamento/Create", [
            'titulo' => 'Detalles de Departamento',
            'routeName' => $this->routeName,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:departamentos,email',

        ], [
            'email.unique' => 'El correo electrónico ya está registrado.',
        ]);

        Departamento::create($validated);

        if ($request->filled('redirect')) {
            return redirect($request->input('redirect'))
                ->with('success', 'Departamento creada correctamente');
        }

        return redirect()->route($this->routeName . 'index')->with('success', 'Departamento creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamento)
    {
        return Inertia::render("Departamento/Edit", [
            'titulo' => 'Editar Departamento',
            'departamento' => $departamento,
            'routeName' => $this->routeName
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departamento $departamento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'email' => 'required|email|max:50',
        ]);
        $departamento->update($validated);

        return redirect()->route($this->routeName . 'index')->with('success', 'Departamento actualizada con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departamento $departamento)
    {
        $departamento->delete();
    }
}
