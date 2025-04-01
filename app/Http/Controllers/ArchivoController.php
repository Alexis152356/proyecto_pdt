<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArchivoController extends Controller
{
    // Tipos de documentos permitidos
    private $tiposPermitidos = [
        'cv' => 'Currículum Vitae',
        'carta_invitacion' => 'Carta de Invitación',
        'acta_nacimiento' => 'Acta de Nacimiento',
        'ine' => 'INE/Identificación',
        'curp' => 'CURP',
        'rfc' => 'RFC',
        'comprobante_domicilio' => 'Comprobante de Domicilio',
        'certificado_medico' => 'Certificado Médico'
    ];

    public function index()
    {
        $documentos = Auth::user()->archivos()
                        ->get()
                        ->keyBy('tipo'); // Esto agrupa por tipo para el acceso $documentos[$tipo]
        
        return view('admin.subir_archivos', [
            'documentos' => $documentos,
            'tiposDocumentos' => $this->tiposPermitidos
        ]);
    }
    // Almacenar nuevo archivo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:' . implode(',', array_keys($this->tiposPermitidos)),
            'documento' => 'required|file|mimes:pdf|max:5120'
        ]);
    
        $usuario = Auth::user();
        $archivo = $request->file('documento');
    
        // Generar nombre único para el archivo
        $nombreArchivo = 'doc_'.$usuario->id.'_'.$request->tipo.'_'.time().'.pdf';
        
        // Guardar usando el disco 'public' (esto crea la carpeta si no existe)
        $ruta = $archivo->storeAs('archivos', $nombreArchivo, 'public');
    
        // Eliminar archivo existente del mismo tipo
        $this->eliminarArchivoExistente($usuario->id, $request->tipo);
    
        // Crear registro en la base de datos
        Archivo::create([
            'user_id' => $usuario->id,
            'tipo' => $request->tipo,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta' => $ruta,  // Esto guardará "archivos/nombrearchivo.pdf"
            'mime_type' => $archivo->getMimeType(),
            'tamano' => $archivo->getSize(),
            'estado' => 'pendiente'
        ]);
    
        return back()->with('success', 'Archivo subido correctamente');
    }
    // Mostrar archivo
    public function show($id)
    {
        $archivo = Archivo::findOrFail($id);
        
        if (!Storage::disk('public')->exists($archivo->ruta)) {
            abort(404, 'El archivo no existe');
        }
    
        if (request()->has('download')) {
            return Storage::disk('public')->download($archivo->ruta);
        }
    
        return response()->file(storage_path('app/public/' . $archivo->ruta));
    }

  
    public function destroy($id)
    {
        $archivo = Archivo::findOrFail($id);
    
        // Verificación más detallada
        if (Auth::user()->id !== $archivo->user_id) {
            abort(403, 'No tienes permiso para eliminar este archivo');
        }
    
        try {
            // Eliminar archivo físico
            if (Storage::disk('public')->exists($archivo->ruta)) {
                Storage::disk('public')->delete($archivo->ruta);
            }
    
            // Eliminar registro
            $archivo->delete();
    
            return back()->with('success', 'Archivo eliminado correctamente');
    
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el archivo: '.$e->getMessage());
        }
    }
    // Aprobar archivo (admin)
    public function aprobar($id)
    {
        $archivo = Archivo::findOrFail($id);
        
        $archivo->update([
            'estado' => 'aprobado',
            'comentario' => null,
            'revisado_at' => now(),
            'revisado_por' => Auth::id()
        ]);

        return back()->with('success', 'Archivo aprobado correctamente');
    }

    // Rechazar archivo (admin)
    public function rechazar(Request $request, $id)
    {
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

    // Vista para ver archivos (usuario)
    public function verArchivos()
    {
        // Obtener documentos del usuario autenticado
        $documentos = Auth::user()->archivos()->get();
        
        // Tipos de documentos permitidos (deberías definirlos o pasarlos desde el controlador)
        $tiposDocumentos = [
            'cv' => 'Currículum Vitae',
            'carta_invitacion' => 'Carta de Invitación',
            // ... otros tipos
        ];
    
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
        if (Storage::disk('public')->exists($archivo->ruta)) {
            Storage::disk('public')->delete($archivo->ruta);
        }
        $archivo->delete();
    }
}
}