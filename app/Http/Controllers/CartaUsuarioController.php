<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Carta;

class CartaUsuarioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Obtener o crear la carta del usuario
        $carta = $user->cartas()->firstOrCreate([]);
        
        return view('usuarios.cartas', compact('carta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|file|mimes:pdf|max:2048',
            'tipo' => 'required|in:aceptacion,presentacion'
        ]);

        $user = auth()->user();
        $path = $request->file('documento')->store('cartas', 'public');

        // Obtener o crear la carta del usuario
        $carta = $user->cartas()->firstOrCreate([]);

        if($request->tipo == 'aceptacion') {
            // Eliminar archivo anterior si existe
            if($carta->carta_aceptacion) {
                Storage::disk('public')->delete($carta->carta_aceptacion);
            }
            $carta->carta_aceptacion = $path;
            $carta->estado_aceptacion = 'pendiente';
        } else {
            // Eliminar archivo anterior si existe
            if($carta->carta_presentacion) {
                Storage::disk('public')->delete($carta->carta_presentacion);
            }
            $carta->carta_presentacion = $path;
            $carta->estado_presentacion = 'pendiente';
        }

        $carta->save();

        return back()->with('success', 'Documento subido correctamente');
    }
}