<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\InventarioEquipoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\CertificacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioGeneralController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DashboardController;

//use App\Http\Controllers\ModuleController; //checar si se ocupan borrado
//use App\Http\Controllers\PerfilesController;  borrado

//use App\Http\Controllers\PermissionController;

use App\Http\Controllers\ProfileController; //perfil de usuario

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SistemaController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
// Route::get('/dashboard', function () {

//     return Inertia::render('Dashboard', [
//         'users' => User::all(),

//     ]);
// })->middleware(['auth'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');



Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Seguridad
    // Route::resource('module', ModuleController::class)->parameters(['module' => 'module']);
    //Route::resource('permissions', PermissionController::class)->names('permissions');
    //Route::resource('perfiles', PerfilesController::class)->parameters(['perfiles' => 'perfiles']);

    //Usuarios
    Route::resource('usuarios', controller: UserController::class)->parameters(['usuarios' => 'usuarios']);
    Route::get('/perfil', [UserController::class, 'perfil'])->name('usuarios.perfil');
    Route::post('actualizarPerfil', [UserController::class, 'updatePerfil'])->name('usuarios.update-perfil');

    //Notificaciones 
    //Route::get('/notificaciones', [NotificationController::class, 'index']);
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notificaciones/{id}/marcar-como-leida', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notificaciones/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notificaciones/count-no-leidas', [NotificationController::class, 'NotificationCount'])->name('notifications.unreadCount');



    //Rutas para modulo de inventario  
    Route::get('/inventario/form', [InventarioEquipoController::class, 'mostrar'])->name('inventario.form'); //declarar antes de la ruta principal
    Route::get('/inventario/exportar', [InventarioEquipoController::class, 'exportExcel'])->name('inventario.export');
    Route::resource('inventario', InventarioEquipoController::class);
    Route::post('/inventario/importar', [InventarioEquipoController::class, 'importarExcel'])->name('inventario.importar');
    Route::get('inventario/{id}/responsiva', [InventarioEquipoController::class, 'generarResponsiva'])->name('inventario.responsiva'); //genera pdf

    //Catalogo
    Route::resource('marcas', MarcaController::class);
    Route::resource('departamentos', DepartamentoController::class);


    //Rutas para modulo de sistema
    Route::get('/sistema/form', [SistemaController::class, 'mostrar'])->name('sistema.form'); //declarar antes de la ruta principal
    Route::get('/sistemas/exportar', [SistemaController::class, 'exportExcel'])->name('sistemas.export');
    Route::resource('sistema', controller: SistemaController::class);
    Route::post('/sistema/importar', [SistemaController::class, 'importarExcel'])->name('sistema.importar');

    //Procesos
    Route::resource('procesos', controller: ProcesoController::class);
    //Certificación
    Route::resource('certificacion', CertificacionController::class);


    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('permisos', PermisosController::class);
    Route::resource('modules', ModuleController::class);



    Route::get('/dashboard-ejecutivo', [DashboardController::class, 'dashboardEjecutivo'])
        ->name('dashboard.ejecutivo');

    Route::get('/dashboard-procesos', [DashboardController::class, 'dashboardProcesos'])
        ->name('dashboard.procesos');

    // Agregar esta ruta dentro del grupo de rutas autenticadas
    Route::post('/user/set-active-role', [AuthenticatedSessionController::class, 'setActiveRole'])
        ->name('user.set-active-role');

    // Ruta para la vista de selección de rol después del login
    Route::get('/select-role', function () {
        return Inertia::render('Auth/RoleSelect');
    })->name('role.select');
});

require __DIR__ . '/auth.php';

