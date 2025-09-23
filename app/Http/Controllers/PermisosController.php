<?php

namespace App\Http\Controllers;

use App\Models\Permisos;
use App\Http\Requests\StorePermisosRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Module;

class PermisosController extends Controller
{
    protected string $routeName;
    protected string $source = 'Seguridad/Permisos/';

    protected string $module = 'permiso';
    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'permisos.';
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index()
    {
        //$permisos = Permisos::all();
        $permisos = Permisos::orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render("{$this->source}Index", [
            'permisos' => $permisos,
            'titulo' => 'Permisos del Sistema',
            'routeName' => $this->routeName
        ]);
    }

    public function create()
    {
        $modules = Module::all();
        return Inertia::render("{$this->source}Create", [
            'modules' => $modules,
            'titulo' => 'Crear Permiso',
            'routeName' => $this->routeName
        ]);
    }

    public function store(StorePermisosRequest $request)
    {
        //dd(request());
        $permiso = Permisos::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'description' => $request->description,
            'module_key' => $request->module_key
        ]);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Permiso creado exitosamente');
    }

    public function edit(Permisos $permiso)
    {
        $modules = Module::all();
        return Inertia::render("{$this->source}Edit", [
            'permiso' => $permiso,
            'modules' => $modules,
            'titulo' => 'Editar Permiso',
            'routeName' => $this->routeName
        ]);
    }

    public function update(StorePermisosRequest $request, Permisos $permiso)
    {
        $permiso->update([
            'name' => $request->name,
            'description' => $request->description,
            'module_key' => $request->module_key
        ]);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Permiso actualizado exitosamente');
    }

    public function destroy(Permisos $permiso)
    {
        $permiso->delete();
        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Permiso eliminado exitosamente');
    }
}