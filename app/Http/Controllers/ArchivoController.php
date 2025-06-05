<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Archivo;
use App\Models\TipoDocumento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchivoController extends Controller
{
    protected $tiposDocumentos = [];

    public function __construct()
    {
        // Cargar tipos de documentos desde la base de datos
        $this->tiposDocumentos = TipoDocumento::where('activo', true)
            ->pluck('nombre', 'clave')
            ->toArray();
    }

    public function index()
    {
        if (!Auth::check()) {
            abort(403, 'Debes iniciar sesión');
        }

        $documentos = Auth::user()->archivos()
                        ->get()
                        ->keyBy('tipo');
        
        return view('admin.subir_archivos', [
            'documentos' => $documentos,
            'tiposDocumentos' => $this->tiposDocumentos
        ]);
    }

    public function addDocumentType(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión'], 403);
        }

        $validated = $request->validate([
            'clave' => 'required|string|max:100|unique:tipo_documentos,clave',
            'nombre' => 'required|string|max:150'
        ]);

        // Crear el nuevo tipo de documento
        $tipoDocumento = TipoDocumento::create([
            'clave' => $validated['clave'],
            'nombre' => $validated['nombre'],
            'custom' => true,
            'activo' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de documento creado exitosamente',
            'tipo' => $tipoDocumento->clave,
            'nombre' => $tipoDocumento->nombre
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Debes iniciar sesión para subir archivos');
        }

        $usuario = Auth::user();
        
        $validated = $request->validate([
            'tipo' => 'required|exists:tipo_documentos,clave',
            'documento' => 'required|file|mimes:pdf|max:5120'
        ]);

        try {
            $archivo = $request->file('documento');
            $nombreArchivo = 'doc_'.$usuario->id.'_'.Str::slug($request->tipo).'_'.time().'.pdf';
            $ruta = $archivo->storeAs('archivos', $nombreArchivo, 'public');

            $this->eliminarArchivoExistente($usuario->id, $request->tipo);

            Archivo::create([
                'user_id' => $usuario->id,
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

    // Obtener los tipos de documentos desde la base de datos
    $tiposDocumentos = TipoDocumento::where('activo', true)
                      ->pluck('nombre', 'clave')
                      ->toArray();

    return view('usuarios.ver_archivos', [
        'documentos' => $documentos,
        'tiposDocumentos' => $tiposDocumentos
    ]);
}

    // ... (mantén los demás métodos show, destroy, aprobar, rechazar, verArchivos iguales)

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