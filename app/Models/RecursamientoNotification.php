<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class RecursamientoNotification extends Model
{
    protected $table = 'notifications'; // Nombre de tu tabla de notificaciones

    protected $casts = [
        'data' => 'array', // Esto indica a Laravel que 'data' debe ser tratado como un array
    ];

    // Aquí puedes definir cualquier otra configuración o relación que necesites
    public function notifications()
{
    return $this->hasMany(Notification::class);
}
}
