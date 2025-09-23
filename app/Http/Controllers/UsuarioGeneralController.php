<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUser1Request;
use App\Notifications\CredencialesEstudianteNotification;

class UsuarioGeneralController extends Controller
{
    protected string $routeName = 'usuarios-sistema.';
    protected string $source = 'Seguridad/UsuariosGenerales/';
    protected string $module = 'usuarios-sistema';

    public function __construct()
    {
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index2()
    {
        $usuarios = User::role('Desarrollador')->with('roles')->paginate(8);

        return Inertia::render("{$this->source}Index", [
            'usuarios' => $usuarios,
            'titulo' => 'Usuarios Generales',
            'routeName' => $this->routeName,
        ]);
    }
    
    public function index(Request $request)
    {
        $query = User::role('Desarrollador')->with('roles');

        if ($request->has('search') && $request->search !== null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

       // $usuarios = $query->paginate(8)->withQueryString();
        $usuarios = $query->orderBy('id','desc')->paginate(8)->withQueryString();


        return Inertia::render("{$this->source}Index", [
            'usuarios' => $usuarios,
            'titulo' => 'Usuarios Generales',
            'routeName' => $this->routeName,
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return Inertia::render("{$this->source}Create", [
            'titulo' => 'Crear Usuario de Sistema',
            'routeName' => $this->routeName,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'numero' => $request->numero,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' => 'Usuario',
            'role' => $request->role,  // Guardar el rol que viene del formulario            

        ]);

        //$user->assignRole('Usuario');
        $user->assignRole($request->role);
        $user->notify(new CredencialesEstudianteNotification($request->email, $request->password));


        return redirect()->route($this->routeName . 'index')->with('message', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);

        return Inertia::render("{$this->source}Edit", [
            'usuario' => $usuario,
            'titulo' => 'Editar Usuario del Sistema',
            'routeName' => $this->routeName,
        ]);
    }

    public function update(UpdateUser1Request $request, $id)
    {
        //dd($request->all());

        $usuario = User::find($id);

        $usuario->update($request->all());

        return redirect()->route($this->routeName . 'index')->with('message', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route($this->routeName . 'index')->with('message', 'Usuario eliminado.');
    }
}
