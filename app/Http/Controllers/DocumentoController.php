<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    // Tipos de documentos configurados según tu migración
    private $tiposDocumentos = [
        'contrato' => 'CONTRATO (Documento principal)', 
        'cv' => 'CV (Currículum Vitae)',
        'carta_invitacion' => 'Carta de invitación del Corporativo AB FORTI (firmada)',
        'acta_nacimiento' => 'Acta de Nacimiento',
        'ine' => 'INE',
        'curp' => 'CURP',
        'rfc' => 'RFC (Constancia de Situación Fiscal)',
        'nss' => 'Número de Seguridad Social (NSS) - Facultativo',
        'comprobante_estudios' => 'Último Comprobante de Estudios',
        'comprobante_domicilio' => 'Comprobante de Domicilio',
        'cartas_recomendacion' => 'Cartas de Recomendación Laboral/Personales',
        'constancias_cursos' => 'Constancias de Cursos',
        'certificado_medico' => 'Certificado Médico (salud y tipo de sangre)',
        'cuenta_nomina' => 'Cuenta de Nómina en BBVA (# cuenta y Clabe)'
    ];

    /**
     * Muestra el formulario para subir documentos
     */
    public function index()
    {
        $documentos = Auth::user()->documentos()->get()->keyBy('tipo');
        
        return view('usuarios.subir_documentos', [
            'documentos' => $documentos,
            'tiposDocumentos' => $this->tiposDocumentos
        ]);
    }

    /**
     * Almacena un nuevo documento
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'tipo' => 'required|in:' . implode(',', array_keys($this->tiposDocumentos)),
        'documento' => 'required|file|mimes:pdf|max:5120' // 5MB máximo
    ]);

    $usuario = Auth::user();
    $archivo = $request->file('documento');

    // Generar nombre único para el archivo
    $nombreArchivo = 'doc_'.$usuario->id.'_'.$request->tipo.'_'.time().'.pdf';

    // Ruta relativa y absoluta
    $rutaRelativa = 'documentos/'.$nombreArchivo;
    $rutaAbsoluta = storage_path('app/'.$rutaRelativa);

    // Asegurar que el directorio existe
    if (!Storage::exists('documentos')) {
        Storage::makeDirectory('documentos');
    }

    // Mover el archivo con verificación
    if (!$archivo->move(storage_path('app/documentos'), $nombreArchivo)) {
        return back()->with('error', 'Error al guardar el archivo');
    }

    // Eliminar documento existente si existe
    $this->eliminarDocumentoExistente($usuario->id, $request->tipo);

    // Crear registro en la base de datos
    Documento::create([
        'usuario_id' => $usuario->id,
        'tipo' => $request->tipo,
        'nombre_archivo' => $archivo->getClientOriginalName(),
        'ruta_archivo' => $rutaRelativa
    ]);

    return back()->with('success', 'Documento subido correctamente');
}
    /**
     * Elimina un documento
     */
    public function destroy($id)
    {
        $documento = Documento::findOrFail($id);
        
        // Verificar que el documento pertenece al usuario autenticado
        if ($documento->usuario_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para eliminar este documento');
        }

        // Eliminar archivo físico
        if (Storage::exists($documento->ruta_archivo)) {
            Storage::delete($documento->ruta_archivo);
        }

        // Eliminar registro de la base de datos
        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente');
    }

    /**
     * Muestra un documento
     */
    public function show($id)
    {
        $documento = Documento::findOrFail($id);
        $ruta = storage_path('app/' . $documento->ruta_archivo);
        
        if (!file_exists($ruta)) {
            $documento->delete(); // Elimina registro huérfano
            abort(404, 'El PDF no existe en el servidor');
        }
    
        // Solución definitiva para servir archivos
        return response()->file($ruta, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documento->nombre_archivo.'"'
        ]);
    }
private function eliminarDocumentoExistente($usuarioId, $tipo)
{
    $documento = Documento::where('usuario_id', $usuarioId)
                         ->where('tipo', $tipo)
                         ->first();

    if ($documento) {
        $rutaCompleta = storage_path('app/'.$documento->ruta_archivo);
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }
        $documento->delete();
    }
}

public function verDocumentosUsuarios()
{
    $usuarios = Usuario::with('documentos')->get();
    return view('admin.documentos-usuarios', compact('usuarios'));
}


public function gestionarUsuarios()
{
    $usuarios = Usuario::all(); // O cualquier filtro que necesites
    return view('admin.gestionar-usuarios', compact('usuarios'));
}



public function listarUsuarios()
{
    $usuarios = Usuario::withCount('documentos')
                ->orderBy('created_at', 'desc')
                ->get();
    
    return view('admin.usuarios', compact('usuarios'));
}

public function verUsuario($id)
{
    $usuario = Usuario::with('documentos')->findOrFail($id);
    $tiposDocumentos = [
        'cv' => 'CV',
        'carta_invitacion' => 'Carta Invitación',
        // ... otros tipos de documentos
    ];
    
    return view('admin.ver-usuario', compact('usuario', 'tiposDocumentos'));
}

// En DocumentoController.php
public function aprobarDocumento(Request $request, $id)
{
    $documento = Documento::findOrFail($id);
    
    $documento->update([
        'estado' => 'aprobado',
        'comentario' => null,
        'revisado_at' => now(),
        'revisado_por' => Auth::id()
    ]);
    
    return back()->with('success', 'Documento aprobado correctamente');
}

public function rechazarDocumento(Request $request, $id)
{
    $request->validate(['comentario' => 'required|string|max:500']);
    
    $documento = Documento::findOrFail($id);
    
    $documento->update([
        'estado' => 'rechazado',
        'comentario' => $request->comentario,
        'revisado_at' => now(),
        'revisado_por' => Auth::id()
    ]);
    
    return back()->with('success', 'Documento rechazado con comentarios');
}

public function revisarCartas(Request $request, $usuario_id = null)
{
    $query = Carta::with('user');
    
    if ($usuario_id) {
        $query->where('user_id', $usuario_id);
    }
    
    $cartas = $query->get();
    
    return view('admin.cartas.revisar', compact('cartas'));
}
}