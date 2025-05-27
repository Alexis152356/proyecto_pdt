<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 
        'edad', 
        'universidad', 
        'genero', 
        'correo', 
        'password', 
        'foto',
        'remember_token',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'usuario_id');
    }

  public function archivos()
{
    return $this->hasMany(Archivo::class, 'usuario_id'); // Cambiar a usuario_id para consistencia
}

    
    public function cartas()
{
    return $this->hasMany(Carta::class, 'usuario_id'); // Especifica explícitamente la clave foránea
}
}

// Asegúrate que no haya nada (ni siquiera espacios) después de esta línea