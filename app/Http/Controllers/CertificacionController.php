<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificacionRequest;
use App\Http\Requests\UpdateCertificacionRequest;
use App\Http\Requests\UpdateSistemaRequest;
use App\Models\Certificacion;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CertificacionController extends Controller
{
    protected string $routeName = 'certificacion.';
    protected string $module = 'certificacion';

    // Constructor que verifica si el usuario tiene permisos para acceder a la ruta
    public function __construct()
    {
        $this->middleware('auth'); // Requiere autenticación
        $this->routeName = 'certificacion.'; // Define el nombre de la ruta para el formulario
         $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);  
         $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);  
         $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
         $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Certificacion::with('departamento', 'usuario'); // Crea una consulta para obtener todos los sistemas

        // dd($request->all());

        // Si el usuario NO es admin, procesos, que solo vea los sistemas que él creó
        if (!auth()->user()->hasAnyRole(['Admin','Procesos'])) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) { // Verifica si hay un término de búsqueda
            $query->where('nombre', 'like', '%' . $request->search . '%') // Filtra por nombre del sistema
                ->orWhere('departamento_id', 'like', '%' . $request->search . '%') // Filtra por ID del departamento
                ->orWhereHas('departamento', function ($q) use ($request) { // Filtra por nombre del departamento
                    $q->where('nombre', 'like', '%' . $request->search . '%'); // Filtra por nombre del departamento
                });
        }

        $certificaciones = $query->orderBy('id', 'desc') // Ordena los sistemas por ID en orden descendente
            ->paginate(8) // Pagina los sistemas por 8
            ->withQueryString(); // Mantiene el valor en el buscador

        return Inertia::render('Certificacion/Index', [
            'titulo' => 'Lista de Certificaciones',
            'certificaciones' => $certificaciones, // Envía los sistemas al template
            'routeName' => $this->routeName,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::select('id', 'nombre as name')->get(); // Obtiene los departamentos

        return Inertia::render('Certificacion/Create', [ // Renderiza la vista con los departamentos
            'titulo' => 'Crear Registro de Certificación', // Envía el título de la página
            'routeName' => $this->routeName, // Envía el nombre de la ruta para el formulario
            'departamentos' => $departamentos, // Envía los departamentos al template
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificacionRequest $request)
    {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction(); // Inicia una transacción para asegurarse de que se guarden los datos de manera atomica

        try {
            $folder = 'documentos_certificacion'; // Define el folder donde se guardarán los archivos

            if (!Storage::disk('public')->exists($folder)) { // Verifica si el folder existe
                Storage::disk('public')->makeDirectory($folder); // Si no existe, crea el folder
            }

            $certificacion = Certificacion::create($request->validated()); // Crea el registro del sistema con los datos validados


            if ($request->hasFile('ruta_documento')) { // Verifica si se han enviado archivos
                foreach ($request->file('ruta_documento') as $file) { // Itera sobre los archivos enviados
                    $certificacion->archivos()->create([ // Crea un registro de archivo asociado al sistema
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

    // Guarda un archivo en el disco público y devuelve su ruta
    private function storeFile($file, $folder)
    {
        // Genera un nombre único para el archivo
        $originalName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension(); // Obtiene la extensión del archivo
        $fileName = time() . '_' . $originalName . '.' . $extension; // Combina el timestamp con el nombre original y la extensión
        return $file->storeAs($folder, $fileName, 'public'); // Guarda el archivo en el disco público y devuelve la ruta
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificacion $certificacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificacion $certificacion)
    {
        // Crea un array de departamentos para el select
        $departamentos = Departamento::select('id', 'nombre as name')->get();
        $certificacion->load('archivos'); // Carga los archivos asociados al sistema

        return Inertia::render('Certificacion/Edit', [ // Renderiza la vista de edición con los datos del sistema
            'titulo' => 'Editar Registro de Certificación', // Título de la página
            'certificacion' => $certificacion, // Datos del sistema
            'routeName' => $this->routeName, // Nombre de la ruta para el formulario
            'departamentos' => $departamentos, // Departamentos para el select
            'archivosPrincipales' => $certificacion->archivos, // Archivos principales del sistema
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificacionRequest $request, Certificacion $certificacion)
    {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction(); // Inicia una transacción para asegurarse de que se guarden los datos de manera atomica

        try {
            $certificacion->update($request->validated()); // Actualiza los datos del sistema con los datos proporcionados

            if (!empty($request->archivos_a_eliminar)) { // Verifica si hay archivos a eliminar
                // Elimina los archivos seleccionados
                $this->eliminarArchivos($certificacion, $request->archivos_a_eliminar);
            }

            if ($request->hasFile('nuevos_documentos_principales')) { // Verifica si hay archivos nuevos para guardar
                // Guarda los nuevos archivos
                $this->guardarNuevosArchivos($certificacion, $request->file('nuevos_documentos_principales'));
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
    protected function eliminarArchivos(Certificacion $certificacion, array $archivosIds)
    {
        // Obtener los archivos a eliminar de la base de datos por sus IDs
        $archivos = $certificacion->archivos()->whereIn('id', $archivosIds)->get();

        foreach ($archivos as $archivo) { // Recorrer cada archivo
            // Eliminar los archivos del storage
            Storage::disk('public')->delete($archivo->ruta_archivo);
            $archivo->delete(); // Eliminar registro de la base de datos
        }
    }

    // Guarda nuevos archivos en el storage y registros en la base de datos
    protected function guardarNuevosArchivos(Certificacion $certificacion, array $archivos)
    {
        $folder = 'documentos_certificacion'; // Define el folder donde se guardarán los archivos
        foreach ($archivos as $archivo) { // Recorre cada archivo            
            $certificacion->archivos()->create([ // Crea un registro en la tabla DocumentoSistema
                'ruta_archivo' => $this->storeFile($archivo, $folder), // Ruta del archivo en el storage
                'nombre_original' => $archivo->getClientOriginalName(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificacion $certificacion)
    {
        DB::beginTransaction(); // Inicia una transacción para asegurar que todas las operaciones se completen correctamente

        try {
            foreach ($certificacion->archivos as $archivo) { // Itera a través de los archivos asociados al sistema
                if ($archivo->ruta_archivo && Storage::disk('public')->exists($archivo->ruta_archivo)) { // Verifica si el archivo existe en el disco público
                    Storage::disk('public')->delete($archivo->ruta_archivo); // Elimina el archivo del disco
                }
                $archivo->delete(); // Eliminar el registro del archivo en la base de datos
            }

            $certificacion->delete(); // Eliminar el documento en sí
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
