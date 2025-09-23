<?php

namespace App\Http\Controllers;

use App\Models\Sistema;
use App\Http\Requests\StoreSistemaRequest;
use App\Http\Requests\UpdateSistemaRequest;
use App\Imports\InformacionSistemaImport;
use App\Exports\SistemasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SistemaController extends Controller
{
    // Guarda el nombre de la ruta para el formulario
    private string $routeName; // Nombre de la ruta para el formulario
    protected string $module = 'sistema'; // Nombre del módulo para los permisos

    // Constructor que verifica si el usuario tiene permisos para acceder a la ruta
    public function __construct()
    {
        $this->middleware('auth'); // Requiere autenticación
        $this->routeName = 'sistema.'; // Define el nombre de la ruta para el formulario

         $this->middleware("permission:{$this->module}.index")->only(['index', 'show']);  
         $this->middleware("permission:{$this->module}.store")->only(['store', 'create']);  
         $this->middleware("permission:{$this->module}.update")->only(['edit', 'update']);
         $this->middleware("permission:{$this->module}.delete")->only(['destroy']);
    }

    // Muestra la lista de sistemas
    public function index(Request $request)
    {
        $query = Sistema::with('departamento', 'usuario'); // Crea una consulta para obtener todos los sistemas
        // Si el usuario NO es admin, que solo vea los sistemas que él creó
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) { // Verifica si hay un término de búsqueda
            $query->where('nombre', 'like', '%' . $request->search . '%') // Filtra por nombre del sistema
                ->orWhere('departamento_id', 'like', '%' . $request->search . '%') // Filtra por ID del departamento
                ->orWhereHas('departamento', function ($q) use ($request) { // Filtra por nombre del departamento
                    $q->where('nombre', 'like', '%' . $request->search . '%'); // Filtra por nombre del departamento
                });
        }

        $sistemas = $query->orderBy('id', 'desc') // Ordena los sistemas por ID en orden descendente
            ->paginate(8) // Pagina los sistemas por 8
            ->withQueryString(); // Mantiene el valor en el buscador

        return Inertia::render("Sistema/Index", [ // Renderiza la vista con los sistemas y el nombre de la ruta para el formulario
            'titulo' => 'Lista de sistemas', // Envía el título de la página
            'sistemas' => $sistemas, // Envía los sistemas al template
            'routeName' => $this->routeName, // Envía el nombre de la ruta para el formulario
            'filters' => $request->only('search'), // Esto mantiene el valor en el buscador
        ]);
    }

    // Muestra el formulario para crear un nuevo sistema
    public function create()
    {
        $departamentos = Departamento::select('id', 'nombre as name')->get(); // Obtiene los departamentos

        return Inertia::render('Sistema/Create', [ // Renderiza la vista con los departamentos
            'titulo' => 'Crear Registro de Sistema', // Envía el título de la página
            'routeName' => $this->routeName, // Envía el nombre de la ruta para el formulario
            'departamentos' => $departamentos, // Envía los departamentos al template
        ]);
    }

    // Almacena el formulario para crear un nuevo sistema
    public function store(StoreSistemaRequest $request)
    {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction(); // Inicia una transacción para asegurarse de que se guarden los datos de manera atomica

        try {
            $folder = 'documentos_sistema'; // Define el folder donde se guardarán los archivos

            if (!Storage::disk('public')->exists($folder)) { // Verifica si el folder existe
                Storage::disk('public')->makeDirectory($folder); // Si no existe, crea el folder
            }

            $Sistema = Sistema::create($request->validated()); // Crea el registro del sistema con los datos validados


            if ($request->hasFile('ruta_documento')) { // Verifica si se han enviado archivos
                foreach ($request->file('ruta_documento') as $file) { // Itera sobre los archivos enviados
                    $Sistema->archivos()->create([ // Crea un registro de archivo asociado al sistema
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

    public function show(Sistema $sistema)
    {
    }

    // Muestra el formulario para editar un sistema con los datos del sistema
    public function edit(Sistema $sistema)
    {
        // Crea un array de departamentos para el select
        $departamentos = Departamento::select('id', 'nombre as name')->get();
        $sistema->load('archivos'); // Carga los archivos asociados al sistema

        return Inertia::render('Sistema/Edit', [ // Renderiza la vista de edición con los datos del sistema
            'titulo' => 'Editar Registro de Sistema', // Título de la página
            'sistema' => $sistema, // Datos del sistema
            'routeName' => $this->routeName, // Nombre de la ruta para el formulario
            'departamentos' => $departamentos, // Departamentos para el select
            'archivosPrincipales' => $sistema->archivos, // Archivos principales del sistema
        ]);
    }

    // Actualiza los datos del sistema y sus archivos asociados
    public function update(UpdateSistemaRequest $request, Sistema $sistema)
    {
        // dd($request->all()); // Debugging: Verifica los datos recibidos
        DB::beginTransaction(); // Inicia una transacción para asegurarse de que se guarden los datos de manera atomica

        try {
            $sistema->update($request->validated()); // Actualiza los datos del sistema con los datos proporcionados

            if (!empty($request->archivos_a_eliminar)) { // Verifica si hay archivos a eliminar
                // Elimina los archivos seleccionados
                $this->eliminarArchivos($sistema, $request->archivos_a_eliminar);
            }

            if ($request->hasFile('nuevos_documentos_principales')) { // Verifica si hay archivos nuevos para guardar
                // Guarda los nuevos archivos
                $this->guardarNuevosArchivos($sistema, $request->file('nuevos_documentos_principales'));
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
    protected function eliminarArchivos(Sistema $sistema, array $archivosIds)
    {
        // Obtener los archivos a eliminar de la base de datos por sus IDs
        $archivos = $sistema->archivos()->whereIn('id', $archivosIds)->get();

        foreach ($archivos as $archivo) { // Recorrer cada archivo
            // Eliminar los archivos del storage
            Storage::disk('public')->delete($archivo->ruta_archivo);
            $archivo->delete(); // Eliminar registro de la base de datos
        }
    }

    // Guarda nuevos archivos en el storage y registros en la base de datos
    protected function guardarNuevosArchivos(Sistema $sistema, array $archivos)
    {
        $folder = 'documentos_sistema'; // Define el folder donde se guardarán los archivos
        foreach ($archivos as $archivo) { // Recorre cada archivo            
            $sistema->archivos()->create([ // Crea un registro en la tabla DocumentoSistema
                'ruta_archivo' => $this->storeFile($archivo, $folder), // Ruta del archivo en el storage
                'nombre_original' => $archivo->getClientOriginalName(),
            ]);
        }
    }

    // Función para eliminar un sistema
    public function destroy(Sistema $sistema)
    {
        DB::beginTransaction(); // Inicia una transacción para asegurar que todas las operaciones se completen correctamente

        try {
            foreach ($sistema->archivos as $archivo) { // Itera a través de los archivos asociados al sistema
                if ($archivo->ruta_archivo && Storage::disk('public')->exists($archivo->ruta_archivo)) { // Verifica si el archivo existe en el disco público
                    Storage::disk('public')->delete($archivo->ruta_archivo); // Elimina el archivo del disco
                }
                $archivo->delete(); // Eliminar el registro del archivo en la base de datos
            }

            $sistema->delete(); // Eliminar el documento en sí
            DB::commit(); // Confirma la transacción si todo ha ido bien

            // Redirige a la lista de sistemas
            return redirect()->route($this->routeName . 'index')->with('success', 'Registro eliminado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack(); // Si hay un error, deshace la transacción y muestra un mensaje de error
            // Redirige de vuelta con un mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al eliminar el sistema: ' . $e->getMessage()]);
        }
    }

    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new InformacionSistemaImport, $request->file('archivo'));


        return redirect()->back()->with('success', 'Archivo importado correctamente.');
    }

    public function mostrar()
    {
        return Inertia::render('Sistema/Importar');
    }

    public function exportExcel(Request $request) 
    {
        $filters = $request->all(); // Obtener los filtros del request
        return Excel::download(new SistemasExport($filters), 'sistemas_' . now()->format('Y-m-d') . '.xlsx');
    }
}
