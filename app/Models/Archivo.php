<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',
        'nombre_original',
        'ruta',
        'mime_type',
        'tamano',
        'estado',
        'comentario',
        'revisado_at',
        'revisado_por'
    ];

    protected $casts = [
        'revisado_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    // Accesores
    public function getTamanoFormateadoAttribute()
    {
        $bytes = $this->tamano;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFechaRevisadoFormateadaAttribute()
    {
        return $this->revisado_at ? $this->revisado_at->format('d/m/Y H:i') : null;
    }
}