<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'carta_aceptacion',
        'respuesta_aceptacion',
        'estado_aceptacion',
        'comentario_aceptacion',
        'carta_presentacion',
        'respuesta_presentacion',
        'estado_presentacion',
        'comentario_presentacion'
    ];

    // En app/Models/Carta.php
public function user()
{
    return $this->belongsTo(User::class)->withDefault([
        'name' => 'Usuario eliminado'
    ]);
}
}