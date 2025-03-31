<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Asegúrate de importar el modelo Usuario

class AdminController extends Controller
{
    // Mostrar la lista de usuarios
    public function gestionarUsuarios()
    {
        $usuarios = Usuario::all(); // Obtener todos los usuarios
        return view('admin.gestionar-usuarios', compact('usuarios'));
    }

  

    public function verUsuario($id)
{
    $usuario = Usuario::with('documentos')->findOrFail($id);
    $tiposDocumentos = [
        'cv' => 'CV',
        'carta_invitacion' => 'Carta Invitación',
        // ... todos los demás tipos que definiste
    ];
    
    return view('admin.ver-usuario', compact('usuario', 'tiposDocumentos'));
}

public function verDocumentosUsuarios()
{
    // Obtener todos los usuarios con sus documentos
    $usuarios = Usuario::with('documentos')->whereHas('documentos')->get();
    
    // Tipos de documentos para mostrar nombres legibles
    $tiposDocumentos = [
        'cv' => 'CV (Currículum Vitae)',
        'carta_invitacion' => 'Carta de invitación',
        // ... agregar todos los tipos que necesites
    ];
    
    return view('admin.ver-usuario', compact('usuarios', 'tiposDocumentos'));
}


// In App\Http\Controllers\AdminController.php
public function listarUsuarios()
{
    $usuarios = Usuario::withCount('documentos')
                ->orderBy('created_at', 'desc')
                ->get();
    
    return view('admin.usuarios', compact('usuarios'));
}
}