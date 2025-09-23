<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = ['nombre', 'email'];

    public function routeNotificationForMail()
    {
        return $this->email;
    }
    public function procesos(): HasMany
    {
        return $this->hasMany(Proceso::class, 'departamento_id');
    }
}
