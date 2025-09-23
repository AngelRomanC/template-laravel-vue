<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModuleController extends Controller
{
    protected string $routeName;
    protected string $source = 'Seguridad/Modules/';
     protected string $module = 'module';
    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'modules.';
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }
    public function index()
    {
        $modules = Module::orderBy('id', 'desc')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render("{$this->source}Index", [
            'modules' => $modules,
            'titulo' => 'Módulos del Sistema',
            'routeName' => $this->routeName
        ]);
    }

    public function create()
    {
        return Inertia::render("{$this->source}Create", [
            'titulo' => 'Crear Módulo',
            'routeName' => $this->routeName
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|unique:modules,key',
            'description' => 'nullable|string'
        ]);

        Module::create($request->all());

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Módulo creado exitosamente');
    }

    public function edit(Module $module)
    {
        return Inertia::render("{$this->source}Edit", [
            'module' => $module,
            'titulo' => 'Editar Módulo',
            'routeName' => $this->routeName
        ]);
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|unique:modules,key,' . $module->id,
            'description' => 'nullable|string'
        ]);

        $module->update($request->all());

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Módulo actualizado exitosamente');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Módulo eliminado exitosamente');
    }
}