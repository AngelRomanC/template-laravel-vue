<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Notifications\CredentialsNotification;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Inertia\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    protected string $routeName;
    protected string $source;
    protected string $module = 'users';
    protected User $model;

    public function __construct()
    {
        $this->routeName = "users.";
        $this->source = "Seguridad/Usuarios/";
        $this->model = new User();
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);

    }


    public function index(Request $request): Response
    {

        $query = User::query()->with('roles');

        if ($request->has('search') && $request->search !== null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('id', 'desc')->paginate(8)->withQueryString();

        return Inertia::render("{$this->source}Index", [
            'titulo' => 'Usuarios ',
            'users' => $users,
            'routeName' => $this->routeName,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {

        return Inertia::render("Seguridad/Usuarios/Create", [
            'titulo' => 'Agregar Usuario ',
            'routeName' => $this->routeName,
            'roles' => Role::orderBy('name')->select('id', 'name', )->get(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $newUser = User::create($request->validated());
        $newUser->syncRoles($request->roles);

        $newUser->notify(instance: new CredentialsNotification(
            $request->email,
            $request->password
        ));

        return redirect()->route("{$this->routeName}index")->with('success', 'Usuario Admin generado con éxito');
    }
    public function edit(User $user)
    {
        //$usuario = User::with('roles:id,name')->findOrFail($id);
        $user->load('roles:id,name');

        return Inertia::render("Seguridad/Usuarios/Edit", [
            'titulo' => 'Modificar Usuario ',
            'usuario' => $user,
            'routeName' => $this->routeName,
            'roles' => Role::orderBy('name')->select('id', 'name', )->get(),
        ]);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        //dd($user);
        $data = $request->validated();

        // Si envías password desde el formulario, lo encriptas y actualizas
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect()->route("{$this->routeName}index")->with('success', '¡Usuario Admin actualizado correctamente!');
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->delete();

        return redirect()->route("{$this->routeName}index")->with('success', 'Usuario eliminado con éxito');
    }
}
