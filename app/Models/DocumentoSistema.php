<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoSistema extends Model {
    use HasFactory; // Permite crear modelos con factorias

    // Nombre de las columnas de la tabla en la base de datos
    protected $fillable = [
        'documento_id',
        'ruta_archivo',
        'nombre_original',       
    ];

    // Relacion con el modelo Sistema
    public function sistema() {
        return $this->belongsTo(Sistema::class); // Relacion muchos a uno con el modelo Sistema
    }
}
