<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id', // Asegúrate que coincida con la migración
        'tipo',
        'nombre_archivo',
        'ruta_archivo'
    ];

    // Especifica explícitamente la clave foránea
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
        //                clave foránea aquí ^^^^^^^^^^
    }
}