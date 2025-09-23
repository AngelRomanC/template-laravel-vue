<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Asegúrate de importar el modelo User
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        // Obtener las notificaciones no leídas del usuario autenticado
        $notifications = $user->unreadNotifications;

        // Devolver la vista utilizando Inertia con las notificaciones
        return Inertia::render('Notificaciones/Index', [
            'notifications' => $notifications->toArray()
        ]);

        // Devolver las notificaciones como un objeto JSON
            //return response()->json(['notifications' => $notifications->toArray()]);

    }

    public function markAsRead($id)
    {
        // Buscar la notificación por su ID entre las notificaciones del usuario autenticado
        $notification = Auth::user()->notifications->find($id);

        // Verificar si la notificación fue encontrada
        if (!$notification) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        // Marcar la notificación como leída
        $notification->markAsRead();

        // Devolver una respuesta JSON indicando que la notificación fue marcada como leída
        return response()->json(['message' => 'Notificación marcada como leída']);
    }

    public function destroy($id)
    {
        // Buscar la notificación por su ID entre las notificaciones del usuario autenticado
        $notification = Auth::user()->notifications->find($id);

        // Verificar si la notificación fue encontrada
        if (!$notification) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        // Eliminar la notificación
        $notification->delete();

        // Devolver una respuesta JSON indicando que la notificación fue eliminada
        return response()->json(['message' => 'Notificación eliminada']);
    }

    public function NotificationCount()
    {
        // Obtener el recuento de notificaciones no leídas del usuario autenticado
        $unread_count = Auth::user()->unreadNotifications->count();

        // Devolver el recuento como respuesta JSON
        return response(['unread_count' => $unread_count]);
        
    }
}
