<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'tipo',
        'nombre_archivo',
        'ruta_archivo',
        'estado',
        'comentario',
        'revisado_at',
        'revisado_por'
    ];

    // Para Laravel 7+ (recomendado)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'revisado_at' => 'datetime'
    ];

    // Para versiones anteriores de Laravel
    protected $dates = [
        'created_at',
        'updated_at',
        'revisado_at'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Método accesor para formatear fechas de manera segura
    public function getFechaRevisadoFormateadaAttribute()
    {
        return $this->revisado_at ? Carbon::parse($this->revisado_at)->format('d/m/Y H:i') : null;
    }

    public function getFechaCreacionFormateadaAttribute()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y H:i') : null;
    }


 
}