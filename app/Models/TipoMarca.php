<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMarca extends Model
{
    //
     use HasFactory;

    protected $fillable = ['marca_id', 'tipo'];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

}
