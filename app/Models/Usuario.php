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
        'email_verified_at' // Añadir si usas verificación de email
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
        //                clave foránea aquí ^^^^^^^^^^
    }
}