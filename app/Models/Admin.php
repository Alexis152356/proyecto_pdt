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
}