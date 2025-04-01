<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Admin extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = [
        'nombre', 'correo', 'password'
    ];

    protected $hidden = [
        'password',
    ];

    // Definir el guardia personalizado
    protected $guard = 'admin';

    // Relación con archivos (si los admins pueden tener archivos)
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'user_id');
    }

    // O si los archivos pertenecen específicamente a admins:
    public function archivosAdmin()
    {
        return $this->hasMany(Archivo::class, 'admin_id'); // Asegúrate que la migración tenga admin_id
    }
}