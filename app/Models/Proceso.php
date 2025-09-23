<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    //
    protected $fillable = [
        'nombre',
        'descripcion',
        'departamento_id',
        'fecha_solicitud',
        'fecha_entrega',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'estatus',
        'numero_usuarios',
        'nombre_responsable',
        'nombre_autorizacion',
        'user_id',
    ];


    //analizar esta funcion para que sirve
    protected static function booted()
    {
        static::creating(function ($sistema) {
            // Solo asigna auth()->id() si user_id no está definido
            if (empty($sistema->user_id)) {
                $sistema->user_id = auth()->id();
            }
        });
    }
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class); // Relacion muchos a uno con el modelo Departamento
    }
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }


}
