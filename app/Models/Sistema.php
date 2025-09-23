<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    // Nombre de las columnas de la tabla en la base de datos
    protected $fillable = [
        'nombre',
        'descripcion',
        'departamento_id',
        'url',
        'fecha_creacion',
        'fecha_produccion',
        'estatus',
        'numero_usuarios',
        'nombre_servidor',
        'ip_servidor',
        'sistema_operativo',
        'nombre_servidor_bd',
        'ip_servidor_bd',
        'lenguaje_desarrollo',
        'version_lenguaje',
        'user_id',
    ];

    // Relación con el modelo Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class); // Relacion muchos a uno con el modelo Departamento
    }

    //relación con archivosPDF
    public function archivos()
    {
        return $this->hasMany(DocumentoSistema::class); // Relacion muchos a uno con el modelo DocumentoSistema
    }
    protected static function booted2()
    {
        static::creating(function ($sistema) {
            $sistema->user_id = auth()->id();
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
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
