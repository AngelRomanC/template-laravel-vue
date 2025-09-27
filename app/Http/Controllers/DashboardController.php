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
                'dato' => 42,
            ]);
        }

    }

}