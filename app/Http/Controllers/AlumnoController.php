<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\UpdateAlumnoRequest;
use App\Mail\CrearAlumno;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use App\Models\User;
use App\Notifications\CredencialesEstudianteNotification;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AlumnoController extends Controller
{
    private Alumno $model;
    private string $routeName;
    private string $source;
    private string $module = 'alumno';

    public function __construct()
    {
        $this->middleware('auth');
        $this->source = 'Alumno/';
        $this->model = new Alumno();
        $this->routeName = 'alumno.';
    }

    public function index(Request $request): Response
    {
        $usuarios = User::all();
        $Alumno = $this->model;

        $Alumno = Alumno::with('user')->get();
        $usuarios = User::with('roles')
            ->orderBy('id')
            ->paginate(30)
            ->withQueryString();

        return Inertia::render("Alumno/Index", [
            'titulo' => 'Alumnos  ',
            'Alumno' => $Alumno,
            'usuarios' => $usuarios,
            'routeName' => $this->routeName,
            'loadingResults' => false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios = User::all();
        return Inertia::render("Alumno/Create", [
            'titulo' => 'Agregar alumno',
            'routeName' => $this->routeName,
            'usuarios' => $usuarios, //no se ocupa
            'roles' => Role::pluck('name'), //no se ocupa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAlumnoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlumnoRequest $request)
    {
        $password = $request['password'];
        $user_id = $request->input('user_id');


        $newUser = user::create([
            'name' => $request->input('name'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'numero' => $request->input('numero'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),


        ])->assignRole($request['role']);

        $alumno = new Alumno([
            'cuatrimestre' => $request->input('cuatrimestre'),
            'matricula' => $request->input('matricula'),
        ]);

        $newUser->alumno()->save($alumno);

        //Mail::to($newUser->email)->send(new CrearAlumno($newUser, $password));
        $newUser->notify(instance: new CredencialesEstudianteNotification($request->email, $request->password));


        return redirect()->route("alumno.index")->with('success', 'Alumno generado con éxito');
    }


    public function show(Alumno $alumno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Alumno = Alumno::find($id);
        $usuario = $Alumno->user;

        return Inertia::render("Alumno/Edit", [
            'titulo' => 'Modificar Alumno',
            'Alumno' => $Alumno,
            'usuario' => $usuario,
            'routeName' => $this->routeName,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAlumnoRequest  $request
     * @param  \App\Models\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAlumnoRequest $request, $id)
    {
        $Alumno = Alumno::find($id);
        $usuario = $Alumno->user;
        $Alumno->update($request->all());
        $usuario->update($request->all());
        return redirect()->route("alumno.index")->with('message', 'Alumno actualizado correctamente!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Alumno = Alumno::find($id);
        $usuario = $Alumno->user;
        $Alumno->delete();
        $usuario->delete();
        return redirect()->route("alumno.index")->with('success', 'Alumno eliminada con éxito');
    }
}
