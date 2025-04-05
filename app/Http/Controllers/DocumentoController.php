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
        // Obtiene los documentos del usuario autenticado y los organiza por tipo
        $documentos = Auth::user()->documentos()->get()->keyBy('tipo');
        
        // Devuelve la vista con los documentos y tipos de documentos disponibles
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
        // Validación de los datos recibidos desde el formulario
        $validated = $request->validate([
            'tipo' => 'required|in:' . implode(',', array_keys($this->tiposDocumentos)),
            'documento' => 'required|file|mimes:pdf|max:5120' // 5MB máximo
        ]);

        // Obtener el usuario autenticado
        $usuario = Auth::user();
        $archivo = $request->file('documento');

        // Generar nombre único para el archivo
        $nombreArchivo = 'doc_'.$usuario->id.'_'.$request->tipo.'_'.time().'.pdf';

        // Ruta relativa y absoluta del archivo
        $rutaRelativa = 'documentos/'.$nombreArchivo;
        $rutaAbsoluta = storage_path('app/'.$rutaRelativa);

        // Asegura que el directorio existe
        if (!Storage::exists('documentos')) {
            Storage::makeDirectory('documentos');
        }

        // Mueve el archivo a la carpeta de documentos
        if (!$archivo->move(storage_path('app/documentos'), $nombreArchivo)) {
            return back()->with('error', 'Error al guardar el archivo');
        }

        // Elimina el documento existente si ya existe
        $this->eliminarDocumentoExistente($usuario->id, $request->tipo);

        // Crear un registro en la base de datos para el nuevo documento
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
        // Encuentra el documento en la base de datos
        $documento = Documento::findOrFail($id);
        
        // Verifica que el documento pertenece al usuario autenticado
        if ($documento->usuario_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para eliminar este documento');
        }

        // Elimina el archivo físico de almacenamiento
        if (Storage::exists($documento->ruta_archivo)) {
            Storage::delete($documento->ruta_archivo);
        }

        // Elimina el registro del documento en la base de datos
        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente');
    }

    /**
     * Muestra un documento
     */
    public function show($id)
    {
        // Encuentra el documento
        $documento = Documento::findOrFail($id);
        $ruta = storage_path('app/' . $documento->ruta_archivo);
        
        // Verifica si el archivo existe en el servidor
        if (!file_exists($ruta)) {
            $documento->delete(); // Elimina el registro huérfano
            abort(404, 'El PDF no existe en el servidor');
        }
    
        // Sirve el archivo PDF al navegador
        return response()->file($ruta, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documento->nombre_archivo.'"'
        ]);
    }

    /**
     * Elimina un documento existente si ya existe
     */
    private function eliminarDocumentoExistente($usuarioId, $tipo)
    {
        // Busca un documento con el mismo tipo y usuario
        $documento = Documento::where('usuario_id', $usuarioId)
                             ->where('tipo', $tipo)
                             ->first();

        // Si se encuentra el documento, elimina el archivo físico y el registro en la base de datos
        if ($documento) {
            $rutaCompleta = storage_path('app/'.$documento->ruta_archivo);
            if (file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
            }
            $documento->delete();
        }
    }

    /**
     * Muestra todos los documentos de los usuarios
     */
    public function verDocumentosUsuarios()
    {
        // Obtiene todos los usuarios con sus documentos asociados
        $usuarios = Usuario::with('documentos')->get();
        return view('admin.documentos-usuarios', compact('usuarios'));
    }

    /**
     * Muestra la vista para gestionar usuarios
     */
    public function gestionarUsuarios()
    {
        // Obtiene todos los usuarios, puedes agregar filtros si es necesario
        $usuarios = Usuario::all();
        return view('admin.gestionar-usuarios', compact('usuarios'));
    }

    /**
     * Lista a los usuarios con la cantidad de documentos que tienen
     */
    public function listarUsuarios()
    {
        // Obtiene los usuarios con el conteo de documentos, ordenados por fecha de creación
        $usuarios = Usuario::withCount('documentos')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Muestra los detalles de un usuario específico
     */
    public function verUsuario($id)
    {
        // Encuentra al usuario con los documentos asociados
        $usuario = Usuario::with('documentos')->findOrFail($id);
        $tiposDocumentos = [
            'cv' => 'CV',
            'carta_invitacion' => 'Carta Invitación',
            // ... otros tipos de documentos
        ];
        
        return view('admin.ver-usuario', compact('usuario', 'tiposDocumentos'));
    }

    /**
     * Aprueba un documento
     */
    public function aprobarDocumento(Request $request, $id)
    {
        // Encuentra el documento
        $documento = Documento::findOrFail($id);
        
        // Actualiza el estado del documento a aprobado
        $documento->update([
            'estado' => 'aprobado',
            'comentario' => null,
            'revisado_at' => now(),
            'revisado_por' => Auth::id()
        ]);
        
        return back()->with('success', 'Documento aprobado correctamente');
    }

    /**
     * Rechaza un documento con un comentario
     */
    public function rechazarDocumento(Request $request, $id)
    {
        // Valida el comentario
        $request->validate(['comentario' => 'required|string|max:500']);
        
        // Encuentra el documento
        $documento = Documento::findOrFail($id);
        
        // Actualiza el estado del documento a rechazado con comentario
        $documento->update([
            'estado' => 'rechazado',
            'comentario' => $request->comentario,
            'revisado_at' => now(),
            'revisado_por' => Auth::id()
        ]);
        
        return back()->with('success', 'Documento rechazado con comentarios');
    }

    /**
     * Revisa las cartas (por usuario si se proporciona el ID)
     */
    public function revisarCartas(Request $request, $usuario_id = null)
    {
        // Busca cartas relacionadas con el usuario si se proporciona el ID
        $query = Carta::with('user');
        
        if ($usuario_id) {
            $query->where('user_id', $usuario_id);
        }
        
        $cartas = $query->get();
        
        return view('admin.cartas.revisar', compact('cartas'));
    }
}
