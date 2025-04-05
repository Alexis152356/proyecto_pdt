<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CartaAdminController extends Controller
{
    public function index()
    {
        $cartasIds = Carta::select('usuario_id', DB::raw('MAX(id) as id'))
                        ->groupBy('usuario_id')
                        ->pluck('id');
        
        $cartas = Carta::with('user')
                    ->whereIn('id', $cartasIds)
                    ->get();

        return view('admin.revisar_cartas', compact('cartas'));
    }

    public function subirRespuesta(Request $request, $id, $tipo)
    {
        $validated = $request->validate([
            'documento' => 'required|file|mimes:pdf|max:2048'
        ], [
            'documento.required' => 'Debe seleccionar un archivo PDF',
            'documento.mimes' => 'El archivo debe ser de tipo PDF',
            'documento.max' => 'El archivo no debe exceder los 2MB'
        ]);
    
        try {
            $carta = Carta::findOrFail($id);
            $fieldName = 'respuesta_' . $tipo;
            $estadoField = 'estado_' . $tipo;
            $comentarioField = 'comentario_' . $tipo;
        
            // Eliminar archivo anterior si existe
            if ($carta->$fieldName) {
                $this->eliminarArchivo($carta->$fieldName);
            }
        
            // Guardar el archivo
            $path = $request->file('documento')->store('respuestas', 'public');
            
            // Actualizar la base de datos con estado pendiente
            $carta->update([
                $fieldName => $path,
                $estadoField => 'pendiente',
                $comentarioField => null
            ]);
            
            return back()->with('success', 'Documento subido correctamente. El estado se ha establecido como pendiente.');
            
        } catch (\Exception $e) {
            \Log::error('Error al subir respuesta: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al subir el documento');
        }
    }

    public function eliminarRespuesta($id, $tipo)
    {
        try {
            $carta = Carta::findOrFail($id);
            $fieldName = 'respuesta_' . $tipo;
            $estadoField = 'estado_' . $tipo;
            $comentarioField = 'comentario_' . $tipo;

            if ($carta->$fieldName) {
                // Eliminar archivo físico
                $this->eliminarArchivo($carta->$fieldName);
                
                // Limpiar campos en la base de datos y establecer estado como pendiente
                $carta->update([
                    $fieldName => null,
                    $estadoField => 'pendiente',
                    $comentarioField => null
                ]);
            }

            return back()->with('success', 'Documento eliminado correctamente. El estado se ha restablecido como pendiente.');
            
        } catch (\Exception $e) {
            \Log::error('Error al eliminar respuesta: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al eliminar el documento');
        }
    }

    public function responder($id, Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:aceptacion,presentacion',
            'accion' => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string|required_if:accion,rechazado|max:500'
        ], [
            'comentario.required_if' => 'Debe proporcionar un motivo para el rechazo',
            'comentario.max' => 'El comentario no debe exceder los 500 caracteres'
        ]);
    
        try {
            DB::beginTransaction();
            
            $carta = Carta::findOrFail($id);
            $tipo = $request->tipo;
            
            $estadoField = 'estado_' . $tipo;
            $comentarioField = 'comentario_' . $tipo;
            
            $carta->$estadoField = $request->accion;
            
            if ($request->accion == 'rechazado') {
                $carta->$comentarioField = $request->comentario;
            } else {
                $carta->$comentarioField = null;
            }
            
            $carta->save();
            
            DB::commit();
            
            $accion = $request->accion == 'aprobado' ? 'aprobada' : 'rechazada';
            return back()->with('success', "La carta de {$tipo} ha sido {$accion} correctamente");
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al responder carta: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al procesar la respuesta');
        }
    }

    private function eliminarArchivo($rutaArchivo)
    {
        // Verifica si la ruta es relativa (sin 'storage/')
        if (!str_starts_with($rutaArchivo, 'storage/')) {
            $rutaArchivo = 'storage/' . $rutaArchivo;
        }

        $rutaRelativa = str_replace('storage/', '', $rutaArchivo);
        
        if (Storage::disk('public')->exists($rutaRelativa)) {
            return Storage::disk('public')->delete($rutaRelativa);
        }
        
        return false;
    }
}