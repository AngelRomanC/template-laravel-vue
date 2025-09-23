<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificacion extends Model
{
    /// Nombre de las columnas de la tabla en la base de datos
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

    public function departamento() // Relación con el modelo Departamento
    {
        return $this->belongsTo(Departamento::class); // Relacion muchos a uno con el modelo Departamento
    }

    public function archivos() //relación con archivosPDF
    {
        return $this->hasMany(DocumentoCertificacion::class); // Relacion muchos a uno con el modelo DocumentoSistema
    }

    protected static function booted2()
    {
        static::creating(function ($sistema) {
            $sistema->user_id = auth()->id();
        });
    }

    public function usuario() // Relación con el modelo Usuario
    {
        return $this->belongsTo(User::class, 'user_id'); // Relacion muchos a uno con el modelo Usuario
    }

        protected static function booted()
    {
        static::creating(function ($sistema) {
            // Solo asigna auth()->id() si user_id no está definido
            if (empty($sistema->user_id)) {
                $sistema->user_id = auth()->id();
            }
        });
    }
}

