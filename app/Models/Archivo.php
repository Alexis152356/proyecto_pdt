<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // Asegúrate que coincida con la migración
        'tipo',
        'nombre_original',
        'ruta',
        'mime_type',
        'tamano',
        'estado',
        'comentario',
        'revisado_at',
        'revisado_por'      // Debe coincidir con la migración
    ];

    protected $casts = [
        'revisado_at' => 'datetime',
    ];

    // Relación con el usuario propietario (CORREGIDA)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id'); // Coincide con fillable y migración
    }

    // Relación con el revisor (CORREGIDA)
    public function revisor()
    {
        return $this->belongsTo(Usuario::class, 'revisado_por'); // Usa mismo modelo que usuario
    }

    // Eliminar relación admin() si no es necesaria
    // O corregirla si existe tabla admins:
    /*
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    */

    // Accesores (perfectos, se mantienen)
    public function getTamanoFormateadoAttribute()
    {
        $bytes = $this->tamano;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getFechaRevisadoFormateadaAttribute()
    {
        return $this->revisado_at?->format('d/m/Y H:i');
    }
}