<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioEquipo extends Model
{
    //
    protected $fillable = [
        'fecha_registro',
        'nombre_persona',
        'departamento_id',
        'puesto',
        'tipo_pc',
        'marca_equipo',
        'sistema_operativo',
        'procesador',
        'tarjeta_madre',
        'tarjeta_grafica',
        'datos_tarjeta_grafica',
        'tipo_ram',
        'capacidad_ram',
        'marca_ram',
        'tipo_disco',
        'capacidad_disco',
        'teclado_mouse',
        'camara_web',
        'otro_periferico',

        'software_remoto',
        'id_remoto',
        'password_remoto',

        'user_id',
        'observaciones',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class); //Cargar relación
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fecha_registro = now();
        });
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
