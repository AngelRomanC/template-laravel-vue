<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\UserDepartamento;
use App\Models\Usuario;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUsuarioRequest;

use App\Notifications\CredencialesEstudianteNotification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
//use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Modulo;
use Inertia\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    protected string $routeName;
    protected string $source;
    protected string $module = 'usuarios';
    protected User $model;

    public function __construct()
    {
        $this->routeName = "usuarios.";
        $this->source = "Seguridad/Usuarios/";
        $this->model = new User();
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);

    }


    public function index(Request $request): Response
    {
        // $query = User::where('role', 'Admin');
        // $query = User::query();
        $query = User::query()->with('roles');

        // Si el usuario logueado es de tipo "Procesos", filtramos solo ejecutivos
        if (auth()->user()->hasRole('Procesos')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Ejecutivo');
            });
        }


        // Aplicar búsqueda si hay un parámetro `search`
        if ($request->has('search') && $request->search !== null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $admin = $query->orderBy('id', 'desc')->paginate(8)->withQueryString();

        return Inertia::render("{$this->source}Index", [
            'titulo' => 'Usuarios ',
            'admin' => $admin,
            'routeName' => $this->routeName,
            'filters' => $request->only(['search']), // Para mantener el valor en el input
        ]);
    }

    public function create()
    {
        if (auth()->user()->hasRole('Procesos')) {
            // Solo permitir rol Ejecutivo
            $roles = Role::where('name', 'Ejecutivo')->get();
            $departamentos = Departamento::select('id', 'nombre as name')->get();

        } else {
            // Todos los roles
            $roles = Role::all();
            $departamentos = null; // No se muestran

        }
        return Inertia::render("Seguridad/Usuarios/Create", [
            'titulo' => 'Agregar Usuario ',
            'routeName' => $this->routeName,
            //'roles' => Role::select('id', 'name')->get(),
            'roles' => $roles,
            'departamentos' => $departamentos,


        ]);
    }

    public function store(StoreUserRequest $request)
    {

        // dd($request);
        $newUser = user::create([
            'name' => $request->input('name'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'numero' => $request->input('numero'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            //'role' => $request->input('role'),
        ]);

        $newUser->syncRoles([$request->input('roles')]); // ahora solo un rol

        // Guardar departamento si el rol es Ejecutivo
        if ($request->departamento_id) {
            UserDepartamento::create([
                'user_id' => $newUser->id,
                'departamento_id' => $request->departamento_id,
            ]);
        }


        $newUser->notify(instance: new CredencialesEstudianteNotification($request->email, $request->password));

        return redirect()->route("usuarios.index")->with('success', 'Usuario Admin generado con éxito');
    }
    public function edit($id)
    {
        //$user = auth()->user();
        if (auth()->user()->hasRole('Procesos')) {
            // Solo permitir rol Ejecutivo
            $roles = Role::where('name', 'Ejecutivo')->get();
            $departamentos = Departamento::select('id', 'nombre as name')->get();

        } else {
            // Todos los roles
            $roles = Role::all();
            $departamentos = null; // No se muestran

        }
        //$usuario = User::with('roles')->find($id);
        $usuario = User::with(['roles', 'departamento'])->find($id);


        return Inertia::render("Seguridad/Usuarios/Edit", [
            'titulo' => 'Modificar Usuario ',
            'usuario' => $usuario,
            'routeName' => $this->routeName,
            'roles' => $roles, // Roles disponibles
            'departamentos' => $departamentos,

        ]);
    }
    public function update(UpdateUserRequest $request, $id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        $data = $request->only(['name', 'apellido_paterno', 'apellido_materno', 'numero', 'email']);

        // Si envías password desde el formulario, lo encriptas y actualizas
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $usuario->update($data);

        $usuario->syncRoles($request->input('roles'));

        // Actualizar departamento si aplica
        if ($request->filled('departamento_id')) {
            UserDepartamento::updateOrCreate(
                ['user_id' => $usuario->id],
                ['departamento_id' => $request->departamento_id]
            );
        } else {
            // Si quitas el departamento, lo eliminamos
            UserDepartamento::where('user_id', $usuario->id)->delete();
        }

        return redirect()->route("usuarios.index")->with('success', '¡Usuario Admin actualizado correctamente!');
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado con éxito');
    }
}
