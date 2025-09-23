<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcesoRequest;
use App\Http\Requests\UpdateProcesoRequest;
use App\Models\Departamento;
use App\Models\Proceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProcesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected string $routeName;
    protected string $module = 'proceso'; //Nombre en singular buena práctica

    public function __construct()
    {
        $this->middleware('auth');
        $this->routeName = 'procesos.';
        $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);
        $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);
        $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }
    public function index(Request $request)
    {

        $query = Proceso::with('departamento'); // Cargar la relación

        if (!auth()->user()->hasAnyRole(['Admin', 'Procesos'])) {
            $user = auth()->user();

            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);

                if ($user->departamento_id) {
                    $q->orWhere('departamento_id', $user->departamento_id);
                }
            });
        }

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                ->orWhereHas('departamento', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->search . '%');
                });
        }

        $procesos = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Proceso/Index', [
            'titulo' => 'Lista de Procesos',
            'routeName' => $this->routeName,
            'filters' => $request->only('search'),
            'procesos' => $procesos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::select('id', 'nombre as name')->get();
        $user = auth()->user();

        return Inertia::render('Proceso/Create', [
            'titulo' => 'Crear Registro de Proceso',
            'routeName' => $this->routeName,
            'departamentos' => $departamentos,
            'departamento_id' => $user->departamento_id, // se pasa directo al form

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcesoRequest $request)
    {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction(); // Inicia una transacción para asegurarse de que se guarden los datos de manera atomica

        try {
            $folder = 'documentos_procesos'; // Define el folder donde se guardarán los archivos

            if (!Storage::disk('public')->exists($folder)) { // Verifica si el folder existe
                Storage::disk('public')->makeDirectory($folder); // Si no existe, crea el folder
            }

            $proceso = Proceso::create($request->validated()); // Crea el registro del sistema con los datos validados


            if ($request->hasFile('ruta_documento')) { // Verifica si se han enviado archivos
                foreach ($request->file('ruta_documento') as $file) { // Itera sobre los archivos enviados
                    $proceso->archivos()->create([ // Crea un registro de archivo asociado al sistema
                        'ruta_archivo' => $this->storeFile($file, $folder), // Guarda el archivo y obtiene su ruta
                        'nombre_original' => $file->getClientOriginalName(), // Guarda el nombre original del archivo
                    ]);
                }
            }

            DB::commit(); // Confirma la transacción exitosa

            // Redirige a la lista de sistemas con un mensaje de éxito
            return redirect()->route($this->routeName . 'index')->with('success', 'Registro creado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack(); // Revierte la transacción en caso de error
            // Redirige de vuelta con un mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el sistema: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    private function storeFile($file, $folder)
    {
        // Genera un nombre único para el archivo
        $originalName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension(); // Obtiene la extensión del archivo
        $fileName = time() . '_' . $originalName . '.' . $extension; // Combina el timestamp con el nombre original y la extensión
        return $file->storeAs($folder, $fileName, 'public'); // Guarda el archivo en el disco público y devuelve la ruta
    }
    public function show(Proceso $proceso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proceso $proceso)
    {
        // Crea un array de departamentos para el select
        $departamentos = Departamento::select('id', 'nombre as name')->get();
        $proceso->load('archivos'); // Carga los archivos asociados al sistema



        return Inertia::render('Proceso/Edit', [ // Renderiza la vista de edición con los datos del sistema
            'titulo' => 'Editar Registro de Proceso', // Título de la página
            'proceso' => $proceso, // Datos del sistema
            'routeName' => $this->routeName, // Nombre de la ruta para el formulario
            'departamentos' => $departamentos, // Departamentos para el select
            'archivosPrincipales' => $proceso->archivos, // Archivos principales del sistema
            'userRole' => auth()->user()->getRoleNames()->first(), 

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProcesoRequest $request, Proceso $proceso)
    {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction(); // Inicia una transacción para asegurarse de que se guarden los datos de manera atomica

        try {
            $proceso->update($request->validated()); // Actualiza los datos del sistema con los datos proporcionados

            if (!empty($request->archivos_a_eliminar)) { // Verifica si hay archivos a eliminar
                // Elimina los archivos seleccionados
                $this->eliminarArchivos($proceso, $request->archivos_a_eliminar);
            }

            if ($request->hasFile('nuevos_documentos_principales')) { // Verifica si hay archivos nuevos para guardar
                // Guarda los nuevos archivos
                $this->guardarNuevosArchivos($proceso, $request->file('nuevos_documentos_principales'));
            }

            DB::commit(); // Confirma la transacción

            // Redirigir con mensaje de éxito
            return redirect()->route($this->routeName . 'index')->with('success', 'Información del sistema actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revierte la transacción en caso de error
            // Redirige de vuelta con un mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el sistema: ' . $e->getMessage()]);
        }
    }

    // Elimina un sistema y sus archivos asociados
    protected function eliminarArchivos(Proceso $proceso, array $archivosIds)
    {
        // Obtener los archivos a eliminar de la base de datos por sus IDs
        $archivos = $proceso->archivos()->whereIn('id', $archivosIds)->get();

        foreach ($archivos as $archivo) { // Recorrer cada archivo
            // Eliminar los archivos del storage
            Storage::disk('public')->delete($archivo->ruta_archivo);
            $archivo->delete(); // Eliminar registro de la base de datos
        }
    }

    // Guarda nuevos archivos en el storage y registros en la base de datos
    protected function guardarNuevosArchivos(Proceso $proceso, array $archivos)
    {
        $folder = 'documentos_procesos'; // Define el folder donde se guardarán los archivos
        foreach ($archivos as $archivo) { // Recorre cada archivo            
            $proceso->archivos()->create([ // Crea un registro en la tabla DocumentoSistema
                'ruta_archivo' => $this->storeFile($archivo, $folder), // Ruta del archivo en el storage
                'nombre_original' => $archivo->getClientOriginalName(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proceso $proceso)
    {
        //
        DB::beginTransaction(); // Inicia una transacción para asegurar que todas las operaciones se completen correctamente

        try {
            foreach ($proceso->archivos as $archivo) { // Itera a través de los archivos asociados al sistema
                if ($archivo->ruta_archivo && Storage::disk('public')->exists($archivo->ruta_archivo)) { // Verifica si el archivo existe en el disco público
                    Storage::disk('public')->delete($archivo->ruta_archivo); // Elimina el archivo del disco
                }
                $archivo->delete(); // Eliminar el registro del archivo en la base de datos
            }

            $proceso->delete(); // Eliminar el documento en sí
            DB::commit(); // Confirma la transacción si todo ha ido bien

            // Redirige a la lista de sistemas
            return redirect()->route($this->routeName . 'index')->with('success', 'Registro eliminado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack(); // Si hay un error, deshace la transacción y muestra un mensaje de error
            // Redirige de vuelta con un mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el sistema: ' . $e->getMessage()]);
        }
    }
}
