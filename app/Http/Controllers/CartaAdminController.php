<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CartaAdminController extends Controller
{
    // Función para mostrar todas las cartas más recientes de cada usuario
    public function index()
    {
        // Obtenemos el ID más alto de cada usuario (última carta enviada por usuario)
        $cartasIds = Carta::select('usuario_id', DB::raw('MAX(id) as id'))
                        ->groupBy('usuario_id') // Agrupar por usuario_id para obtener la última carta de cada uno
                        ->pluck('id');
        
        // Obtenemos todas las cartas con el usuario relacionado (usando 'with' para cargar la relación)
        $cartas = Carta::with('user')
                    ->whereIn('id', $cartasIds) // Solo las cartas con los IDs más recientes
                    ->get();

        // Pasamos las cartas a la vista 'admin.revisar_cartas'
        return view('admin.revisar_cartas', compact('cartas'));
    }

    // Función para subir la respuesta a una carta
    public function subirRespuesta(Request $request, $id, $tipo)
    {
        // Validamos que el archivo subido sea un PDF y que no supere los 2MB
        $validated = $request->validate([
            'documento' => 'required|file|mimes:pdf|max:2048'
        ], [
            'documento.required' => 'Debe seleccionar un archivo PDF',
            'documento.mimes' => 'El archivo debe ser de tipo PDF',
            'documento.max' => 'El archivo no debe exceder los 2MB'
        ]);
    
        try {
            // Encontramos la carta con el ID proporcionado
            $carta = Carta::findOrFail($id);
            $fieldName = 'respuesta_' . $tipo; // Definimos el nombre del campo para la respuesta
            $estadoField = 'estado_' . $tipo; // Definimos el campo para el estado de la respuesta
            $comentarioField = 'comentario_' . $tipo; // Definimos el campo para el comentario de la respuesta
        
            // Si ya existe un archivo, lo eliminamos
            if ($carta->$fieldName) {
                $this->eliminarArchivo($carta->$fieldName);
            }
        
            // Guardamos el nuevo archivo en el almacenamiento público
            $path = $request->file('documento')->store('respuestas', 'public');
            
            // Actualizamos los campos de la carta en la base de datos
            $carta->update([
                $fieldName => $path,
                $estadoField => 'pendiente', // Establecemos el estado como pendiente
                $comentarioField => null // Limpiamos el comentario
            ]);
            
            // Retornamos a la vista con un mensaje de éxito
            return back()->with('success', 'Documento subido correctamente. El estado se ha establecido como pendiente.');
            
        } catch (\Exception $e) {
            // En caso de error, se captura y se guarda en el log
            \Log::error('Error al subir respuesta: ' . $e->getMessage());
            // Retornamos a la vista con un mensaje de error
            return back()->with('error', 'Ocurrió un error al subir el documento');
        }
    }

    // Función para eliminar una respuesta previamente subida
    public function eliminarRespuesta($id, $tipo)
    {
        try {
            // Encontramos la carta con el ID proporcionado
            $carta = Carta::findOrFail($id);
            $fieldName = 'respuesta_' . $tipo;
            $estadoField = 'estado_' . $tipo;
            $comentarioField = 'comentario_' . $tipo;

            // Si existe un archivo, lo eliminamos
            if ($carta->$fieldName) {
                $this->eliminarArchivo($carta->$fieldName);
                
                // Limpiamos los campos en la base de datos y restablecemos el estado
                $carta->update([
                    $fieldName => null,
                    $estadoField => 'pendiente', // Estado como pendiente
                    $comentarioField => null
                ]);
            }

            // Retornamos con un mensaje de éxito
            return back()->with('success', 'Documento eliminado correctamente. El estado se ha restablecido como pendiente.');
            
        } catch (\Exception $e) {
            // En caso de error, lo registramos en el log
            \Log::error('Error al eliminar respuesta: ' . $e->getMessage());
            // Retornamos con mensaje de error
            return back()->with('error', 'Ocurrió un error al eliminar el documento');
        }
    }

    // Función para responder una carta (aceptar o rechazar)
    public function responder($id, Request $request)
    {
        // Validamos que los datos de la respuesta sean correctos
        $validated = $request->validate([
            'tipo' => 'required|in:aceptacion,presentacion',
            'accion' => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string|required_if:accion,rechazado|max:500' // Comentario necesario solo si la acción es 'rechazado'
        ], [
            'comentario.required_if' => 'Debe proporcionar un motivo para el rechazo',
            'comentario.max' => 'El comentario no debe exceder los 500 caracteres'
        ]);
    
        try {
            // Comenzamos una transacción para asegurar que los cambios se guarden correctamente
            DB::beginTransaction();
            
            // Encontramos la carta con el ID proporcionado
            $carta = Carta::findOrFail($id);
            $tipo = $request->tipo;
            
            $estadoField = 'estado_' . $tipo;
            $comentarioField = 'comentario_' . $tipo;
            
            // Actualizamos el estado de la carta según la acción seleccionada
            $carta->$estadoField = $request->accion;
            
            // Si la acción es 'rechazado', guardamos el comentario
            if ($request->accion == 'rechazado') {
                $carta->$comentarioField = $request->comentario;
            } else {
                $carta->$comentarioField = null;
            }
            
            // Guardamos los cambios
            $carta->save();
            
            // Confirmamos la transacción
            DB::commit();
            
            // Determinamos si la carta fue aprobada o rechazada
            $accion = $request->accion == 'aprobado' ? 'aprobada' : 'rechazada';
            // Retornamos con el mensaje correspondiente
            return back()->with('success', "La carta de {$tipo} ha sido {$accion} correctamente");
            
        } catch (\Exception $e) {
            // En caso de error, hacemos un rollback de la transacción
            DB::rollBack();
            // Registramos el error en el log
            \Log::error('Error al responder carta: ' . $e->getMessage());
            // Retornamos con un mensaje de error
            return back()->with('error', 'Ocurrió un error al procesar la respuesta');
        }
    }

    // Función para eliminar un archivo
    private function eliminarArchivo($rutaArchivo)
    {
        // Verificamos si la ruta del archivo es relativa (sin 'storage/')
        if (!str_starts_with($rutaArchivo, 'storage/')) {
            $rutaArchivo = 'storage/' . $rutaArchivo;
        }

        // Limpiamos la ruta para eliminar 'storage/'
        $rutaRelativa = str_replace('storage/', '', $rutaArchivo);
        
        // Verificamos si el archivo existe y lo eliminamos
        if (Storage::disk('public')->exists($rutaRelativa)) {
            return Storage::disk('public')->delete($rutaRelativa);
        }
        
        return false; // Retornamos false si no se pudo eliminar
    }
}
