<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Archivo;
use App\Models\Usuario; // Asegúrate de usar el modelo correcto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchivoController extends Controller
{
    // Tipos de documentos permitidos
    private $tiposPermitidos = [
        'PERFIL DE PUESTO TECNÓLOGO' => 'Perfil de Puesto Tecnólogo',
        'GENERALIDADES DEL PROGRAMA DE PDT' => 'Generalidades del Programa de PDT',
        'LISTA DE DOCUMENTOS UPPER' => 'Lista de Documentos UPPER',
        'CONDUCTAS EN ALMACÉN' => 'Conductas en Almacén',
        'FORMATO DE ESTUDIO SOCIOECONOMICO SOLGISTIKA' => 'Formato de Estudio Socioeconómico Solgistika',
        'TRAMITE EN LINEA' => 'Trámite en Línea',
        'FOTOS' => 'Fotos',
        'Ficha de datos para dar de alta' => 'Ficha de Datos para Alta'
    ];

    public function index()
    {
        // Verificación adicional de usuario autenticado
        if (!Auth::check()) {
            abort(403, 'Debes iniciar sesión');
        }

        $documentos = Auth::user()->archivos()
                        ->get()
                        ->keyBy('tipo');
        
        return view('admin.subir_archivos', [
            'documentos' => $documentos,
            'tiposDocumentos' => $this->tiposPermitidos
        ]);
    }

    public function store(Request $request)
    {
        // Validación de autenticación
        if (!Auth::check()) {
            return back()->with('error', 'Debes iniciar sesión para subir archivos');
        }

        $usuario = Auth::user();
        
        // Verificación explícita de que el usuario existe en BD
        if (!$usuario->exists) {
            return back()->with('error', 'Usuario no válido');
        }

        $validated = $request->validate([
            'tipo' => 'required|in:' . implode(',', array_keys($this->tiposPermitidos)),
            'documento' => 'required|file|mimes:pdf|max:5120' // 5MB máximo
        ]);

        try {
            $archivo = $request->file('documento');
            
            // Generar nombre único para el archivo
            $nombreArchivo = 'doc_'.$usuario->id.'_'.Str::slug($request->tipo).'_'.time().'.pdf';
            
            // Guardar usando el disco 'public'
            $ruta = $archivo->storeAs('archivos', $nombreArchivo, 'public');

            // Eliminar archivo existente del mismo tipo
            $this->eliminarArchivoExistente($usuario->id, $request->tipo);

            // Crear registro en la base de datos
            Archivo::create([
                'user_id' => $usuario->id, // Usa el ID del usuario autenticado
                'tipo' => $request->tipo,
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta' => $ruta,
                'mime_type' => $archivo->getMimeType(),
                'tamano' => $archivo->getSize(),
            ]);

            return back()->with('success', 'Archivo subido correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el archivo: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $archivo = Archivo::findOrFail($id);
        
        // Verificar permisos
        if (Auth::id() !== $archivo->user_id && !Auth::user()->esAdmin()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($archivo->ruta)) {
            abort(404, 'El archivo no existe');
        }
    
        return request()->has('download') 
            ? Storage::disk('public')->download($archivo->ruta)
            : response()->file(storage_path('app/public/'.$archivo->ruta));
    }

    public function destroy($id)
    {
        $archivo = Archivo::findOrFail($id);
    
        // Verificación de permisos
        if (Auth::id() !== $archivo->user_id && !Auth::user()->esAdmin()) {
            abort(403);
        }
    
        try {
            if (Storage::disk('public')->exists($archivo->ruta)) {
                Storage::disk('public')->delete($archivo->ruta);
            }
    
            $archivo->delete();
    
            return back()->with('success', 'Archivo eliminado correctamente');
    
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el archivo: '.$e->getMessage());
        }
    }

    public function aprobar($id)
    {
        // Solo para administradores
        if (!Auth::user()->esAdmin()) {
            abort(403);
        }

        $archivo = Archivo::findOrFail($id);
        
        $archivo->update([
            'estado' => 'aprobado',
            'comentario' => null,
            'revisado_at' => now(),
            'revisado_por' => Auth::id()
        ]);

        return back()->with('success', 'Archivo aprobado correctamente');
    }

    public function rechazar(Request $request, $id)
    {
        if (!Auth::user()->esAdmin()) {
            abort(403);
        }

        $request->validate(['comentario' => 'required|string|max:500']);

        $archivo = Archivo::findOrFail($id);
        
        $archivo->update([
            'estado' => 'rechazado',
            'comentario' => $request->comentario,
            'revisado_at' => now(),
            'revisado_por' => Auth::id()
        ]);

        return back()->with('success', 'Archivo rechazado con comentarios');
    }

    public function verArchivos()
    {
        $documentos = Auth::user()->archivos()
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('usuarios.ver_archivos', [
            'documentos' => $documentos,
            'tiposDocumentos' => $this->tiposPermitidos
        ]);
    }

    private function eliminarArchivoExistente($userId, $tipo)
    {
        $archivo = Archivo::where('user_id', $userId)
                         ->where('tipo', $tipo)
                         ->first();

        if ($archivo) {
            try {
                Storage::disk('public')->delete($archivo->ruta);
                $archivo->delete();
            } catch (\Exception $e) {
                logger()->error('Error al eliminar archivo existente: '.$e->getMessage());
            }
        }
    }
}