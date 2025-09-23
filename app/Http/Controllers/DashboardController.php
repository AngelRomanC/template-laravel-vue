<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Departamento;
use App\Models\InventarioEquipo;
use App\Models\Proceso;
use App\Models\Sistema;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            return Inertia::render('Dashboard/Admin', [
                'sistemasCount' => Sistema::count(),
                'equiposCount' => InventarioEquipo::count(),
                'equiposPorTipo' => InventarioEquipo::groupBy('tipo_pc')->selectRaw('tipo_pc, count(*) as total')->pluck('total', 'tipo_pc'),
                'sistemasPorEstatus' => Sistema::groupBy('estatus')->selectRaw('estatus, count(*) as total')->pluck('total', 'estatus'),
                'ultimosEquipos' => InventarioEquipo::with('departamento')->latest()->take(5)->get(),
                'sistemasRecientes' => Sistema::with('departamento')->latest()->take(5)->get()
            ]);
        }

        if ($user->hasRole('Desarrollador')) {
            $sistemas = $user->sistemas()->with('departamento')->get();

            return Inertia::render('Dashboard/Desarrollador', [
                'sistemasAsignados' => $sistemas->count(),
                'sistemasEnDesarrollo' => $sistemas->where('estatus', 'Desarrollo')->count(),
                'sistemasAsignadosLista' => $sistemas->take(5),
            ]);
        }

        if ($user->hasRole('Procesos')) {

            // $procesosQuery = Proceso::query();
            // $certificacionesQuery = Certificacion::query();

            // // Procesos con paginación
            // $procesosPaginated = $procesosQuery->with('departamento')
            //     ->orderBy('created_at', 'desc')
            //     ->paginate(5, ['*'], 'procesos_page')
            //     ->withQueryString();


            // $procesos = $procesosPaginated->getCollection();
            // $procesosPorVencer = $procesos->filter(function ($proceso) {
            //     return Carbon::parse($proceso->fecha_fin_vigencia)->lte(Carbon::now()->addDays(30));
            // })->count();
            // $procesosPorEstatus = $procesos->groupBy('estatus')->map->count();

            // $certificacionesPaginated = $certificacionesQuery->with('departamento')
            //     ->orderBy('created_at', 'desc')
            //     ->paginate(5, ['*'], 'certificaciones_page')
            //     ->withQueryString();

            // $certificaciones = $certificacionesPaginated->getCollection();
            // $certificacionesPorVencer = $certificaciones->filter(function ($certificacion) {
            //     return Carbon::parse($certificacion->fecha_fin_vigencia)->lte(Carbon::now()->addDays(30));
            // })->count();
            // $certificacionesPorEstatus = $certificaciones->groupBy('estatus')->map->count();

            // // Actividades recientes (para el dashboard)
            // $actividadesRecientes = $procesos->concat($certificaciones)
            //     ->sortByDesc('created_at')
            //     ->take(5)
            //     ->map(function ($item) {
            //         $item->__typename = $item instanceof Proceso ? 'Proceso' : 'Certificacion';
            //         return $item;
            //     })
            //     ->values()
            //     ->all();

            // return Inertia::render('Dashboard/Procesos', [
            //     'totalProcesos' => $procesosPaginated->total(),
            //     'procesosPorVencer' => $procesosPorVencer,
            //     'procesosPorEstatus' => $procesosPorEstatus,
            //     'procesosPaginated' => $procesosPaginated,

            //     'totalCertificaciones' => $certificacionesPaginated->total(),
            //     'certificacionesPorVencer' => $certificacionesPorVencer,
            //     'certificacionesPorEstatus' => $certificacionesPorEstatus,
            //     'certificacionesPaginated' => $certificacionesPaginated,

            //     'actividadesRecientes' => $actividadesRecientes
            // ]);

            return redirect()->route('dashboard.procesos');
        }

        if ($user->hasRole('Ejecutivo')) {

            return redirect()->route('dashboard.ejecutivo');

        }
    }
    public function dashboardProcesos()
    {
        $procesosQuery = Proceso::query();
        $certificacionesQuery = Certificacion::query();

        $procesosPaginated = $procesosQuery->with('departamento')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'procesos_page')
            ->withQueryString();

        $procesos = $procesosPaginated->getCollection();
        $procesosPorVencer = $procesos->filter(function ($proceso) {
            return Carbon::parse($proceso->fecha_fin_vigencia)->lte(Carbon::now()->addDays(30));
        })->count();
        $procesosPorEstatus = $procesos->groupBy('estatus')->map->count();

        $certificacionesPaginated = $certificacionesQuery->with('departamento')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'certificaciones_page')
            ->withQueryString();

        $certificaciones = $certificacionesPaginated->getCollection();
        $certificacionesPorVencer = $certificaciones->filter(function ($certificacion) {
            return Carbon::parse($certificacion->fecha_fin_vigencia)->lte(Carbon::now()->addDays(30));
        })->count();
        $certificacionesPorEstatus = $certificaciones->groupBy('estatus')->map->count();

        $actividadesRecientes = $procesos->concat($certificaciones)
            ->sortByDesc('created_at')
            ->take(5)
            ->map(function ($item) {
                $item->__typename = $item instanceof Proceso ? 'Proceso' : 'Certificacion';
                return $item;
            })
            ->values()
            ->all();

        return Inertia::render('Dashboard/Procesos', [
            'totalProcesos' => $procesosPaginated->total(),
            'procesosPorVencer' => $procesosPorVencer,
            'procesosPorEstatus' => $procesosPorEstatus,
            'procesosPaginated' => $procesosPaginated,

            'totalCertificaciones' => $certificacionesPaginated->total(),
            'certificacionesPorVencer' => $certificacionesPorVencer,
            'certificacionesPorEstatus' => $certificacionesPorEstatus,
            'certificacionesPaginated' => $certificacionesPaginated,

            'actividadesRecientes' => $actividadesRecientes
        ]);
    }

    public function dashboardEjecutivo()
    {
        $user = auth()->user();
        $departamentoId = $user->departamento?->departamento_id;

        if (!$departamentoId) {
            return Inertia::render('Dashboard/Ejecutivo', [
                'titulo' => 'Dashboard Ejecutivo',
                'stats' => [],
                'porEstatus' => [],
                'proximosProcesos' => [],
                'procesos' => (object) [
                    'data' => [],
                    'links' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => 0
                ],
            ]);
        }

        $procesos = Proceso::with('departamento', 'archivos')
            ->where('departamento_id', $departamentoId)
            ->orderBy('id', 'desc')
            ->paginate(5, ['*'], 'procesos_ejecutivo')
            ->withQueryString();


        $todosLosProcesos = Proceso::with('archivos')
            ->where('departamento_id', $departamentoId)
            ->get();


        $stats = [
            'total' => $todosLosProcesos->count(),
            'pendientes' => $todosLosProcesos->where('estatus', 'Revisión')->count(),
            'con_documentos' => $todosLosProcesos->filter(fn($p) => $p->archivos->count() > 0)->count(),
            'sin_documentos' => $todosLosProcesos->filter(fn($p) => $p->archivos->count() === 0)->count(),
        ];


        $porEstatus = [
            'Diseño' => $todosLosProcesos->where('estatus', 'Diseño')->count(),
            'Revisión' => $todosLosProcesos->where('estatus', 'Revisión')->count(),
            'Validación' => $todosLosProcesos->where('estatus', 'Validación')->count(),
        ];

        $proximosProcesos = Proceso::with('departamento')
            ->where('departamento_id', $departamentoId)
            ->whereBetween('fecha_fin_vigencia', [now(), now()->addDays(30)])
            ->get();

        $departamento = Departamento::find($departamentoId);

        return Inertia::render('Dashboard/Ejecutivo', [
            'titulo' => "Dashboard Ejecutivo - {$departamento->nombre}",
            'stats' => $stats,
            'porEstatus' => $porEstatus,
            'proximosProcesos' => $proximosProcesos,
            'procesos' => $procesos,
        ]);
    }

}