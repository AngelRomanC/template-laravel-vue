<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    //
    protected $fillable = [
        'ruta_archivo',
        'nombre_original'
    ];
    public function archivable()
    {
        return $this->morphTo();
    }
}
