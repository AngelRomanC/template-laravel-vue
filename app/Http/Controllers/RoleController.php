<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    protected string $routeName;
    protected string $source = 'Seguridad/Roles/';
    protected string $module = 'roles';

    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'roles.';

        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index()
    {
        //$roles = Role::with('permissions')->get();
        $roles = Role::with('permissions') //  Mantienes la relación
            ->orderBy('id', 'desc') // Ordenamiento
            ->paginate(8)           // Paginación de 8 items por página
            ->withQueryString();     // Mantiene parámetros de URL

        return Inertia::render("{$this->source}Index", [
            'roles' => $roles,
            'titulo' => 'Roles y Permisos',
            'routeName' => $this->routeName
        ]);
    }

    public function create()
    {

        return Inertia::render("{$this->source}Create", [
            'titulo' => 'Crear Rol',
            'routeName' => $this->routeName,
            'modules' => Module::orderBy('key')->get(['id', 'name', 'description', 'key']),
            'permissions' => Permission::get(['id', 'name', 'description', 'module_key'])
                ->groupBy('module_key')
                ->toArray(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Rol creado exitosamente');
    }

    public function edit(Role $role)
    {
        //$permissions = Permission::all();
        return Inertia::render("{$this->source}Edit", [
            'role' => $role->load('permissions'),
            'titulo' => 'Editar Rol',
            'routeName' => $this->routeName,
            'modules' => Module::orderBy('key')->get(['id', 'name', 'description', 'key']), // igual que en create
            'permissions' => Permission::get(['id', 'name', 'description', 'module_key'])
                ->groupBy('module_key')
                ->toArray()
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route($this->routeName . 'index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}