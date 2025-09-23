<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    protected $fillable = [
        'cuatrimestre',
        'matricula',
        'user_id'
       ];
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_alumnos', 'alumno_id', 'grupo_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
    public function grupoAlumnos()
{
    return $this->hasMany(Grupo_Alumnos::class);
}

    
}
