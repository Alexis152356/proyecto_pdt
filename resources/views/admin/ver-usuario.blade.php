<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos de {{ $usuario->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            position: relative;
        }
        .document-card {
            background-color: #E0E0E0;
            border-radius: 10px;
            padding: 15px;
            border-left: 5px solid;
            margin-bottom: 20px;
        }
        .document-card.pendiente {
            border-left-color: #ffc107;
        }
        .document-card.aprobado {
            border-left-color: #28a745;
        }
        .document-card.rechazado {
            border-left-color: #dc3545;
        }
        .badge-estado {
            font-size: 0.9rem;
        }
        .comentario-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }
        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 150px;
        }
        #loadingIndicator {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <img src="/img/logo-blue.svg" class="logo" alt="Logo">
        <br> 
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>
                    <i class="fas fa-folder me-2"></i>
                    Documentos de {{ $usuario->nombre }}
                </h1>
                <p class="text-muted">{{ $usuario->correo }}</p>
            </div>
            <div>
                <a href="{{ route('admin.listar.usuarios') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i> Volver a Usuarios
                </a>
                @if(!$usuario->documentos->isEmpty())
                <button id="descargarTodosBtn" class="btn btn-info me-2">
                    <i class="fas fa-file-pdf me-1"></i> Descargar Todos
                </button>
                @endif
                <a href="{{ route('admin.revisar.cartas', ['usuario_id' => $usuario->id]) }}" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i> Revisar Cartas
                </a>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Combinando documentos, por favor espere...</p>
                <div class="progress mt-2" style="width: 50%; margin: 0 auto;">
                    <div id="combineProgress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        @if($usuario->documentos->isEmpty())
            <div class="alert alert-info">
                Este usuario no ha subido documentos aún.
            </div>
        @else
            <div class="row">
                @foreach($usuario->documentos as $documento)
                <div class="col-md-6 mb-3">
                    <div class="document-card {{ $documento->estado }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title">
                                {{ $tiposDocumentos[$documento->tipo] ?? $documento->tipo }}
                            </h5>
                            <span class="badge badge-estado bg-{{ 
                                $documento->estado == 'aprobado' ? 'success' : 
                                ($documento->estado == 'rechazado' ? 'danger' : 'warning') 
                            }}">
                                {{ ucfirst($documento->estado) }}
                            </span>
                        </div>

                        <p class="text-muted small">
                            Subido el {{ $documento->created_at->format('d/m/Y H:i') }}
                            @if($documento->revisado_at)
                                <br>Revisado el {{ $documento->revisado_at->format('d/m/Y H:i') }}
                            @endif
                        </p>

                        @if($documento->estado == 'rechazado' && $documento->comentario)
                        <div class="comentario-box">
                            <strong>Comentario:</strong>
                            <p>{{ $documento->comentario }}</p>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <a href="{{ route('documentos.show', $documento->id) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i> Ver PDF
                                </a>
                                <a href="{{ route('documentos.show', $documento->id) }}?download=1" 
                                   class="btn btn-sm btn-outline-success me-2">
                                    <i class="fas fa-download me-1"></i> Descargar
                                </a>
                            </div>

                            @if($documento->estado != 'aprobado')
                            <form action="{{ route('documentos.aprobar', $documento->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check me-1"></i> Aprobar
                                </button>
                            </form>
                            @endif

                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                data-bs-target="#rechazarModal{{ $documento->id }}">
                                <i class="fas fa-times me-1"></i> Rechazar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal para rechazar -->
                <div class="modal fade" id="rechazarModal{{ $documento->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Rechazar Documento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('documentos.rechazar', $documento->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Documento: <strong>{{ $tiposDocumentos[$documento->tipo] ?? $documento->tipo }}</strong></p>
                                    <div class="mb-3">
                                        <label for="comentario{{ $documento->id }}" class="form-label">Comentario (requerido)</label>
                                        <textarea class="form-control" id="comentario{{ $documento->id }}" 
                                            name="comentario" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Librerías para combinar PDFs -->
    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
    <script src="https://unpkg.com/downloadjs@1.4.7"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const descargarTodosBtn = document.getElementById('descargarTodosBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const progressBar = document.getElementById('combineProgress');
            
            if (descargarTodosBtn) {
                descargarTodosBtn.addEventListener('click', async function() {
                    loadingIndicator.style.display = 'flex';
                    
                    try {
                        // 1. Obtener todos los enlaces de visualización (no los de descarga)
                        const viewLinks = document.querySelectorAll('a.btn-outline-primary[target="_blank"]');
                        const pdfUrls = Array.from(viewLinks).map(link => {
                            // Convertir enlace de visualización a enlace de descarga
                            return link.href.includes('?') ? 
                                   link.href + '&download=1' : 
                                   link.href + '?download=1';
                        });
                        
                        console.log('URLs encontradas:', pdfUrls); // Para depuración
                        
                        if (pdfUrls.length === 0) {
                            alert('No se encontraron documentos para descargar');
                            return;
                        }
                        
                        // 2. Combinar PDFs
                        const { PDFDocument } = PDFLib;
                        const mergedPdf = await PDFDocument.create();
                        
                        // 3. Procesar cada PDF
                        for (let i = 0; i < pdfUrls.length; i++) {
                            try {
                                // Actualizar progreso
                                const progress = Math.round(((i + 1) / pdfUrls.length) * 100);
                                progressBar.style.width = `${progress}%`;
                                
                                // Descargar el PDF (incluye credenciales para autenticación)
                                const response = await fetch(pdfUrls[i], {
                                    credentials: 'include'
                                });
                                
                                if (!response.ok) {
                                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                                }
                                
                                const arrayBuffer = await response.arrayBuffer();
                                const pdfDoc = await PDFDocument.load(arrayBuffer);
                                const pages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                                pages.forEach(page => mergedPdf.addPage(page));
                                
                            } catch (error) {
                                console.error(`Error procesando PDF ${i + 1}:`, error);
                                // Continuar con los demás aunque falle uno
                            }
                        }
                        
                        // 4. Descargar el PDF combinado
                        const mergedPdfBytes = await mergedPdf.save();
                        const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
                        const fileName = `Documentos_${"{{ $usuario->nombre }}".replace(/ /g, '_')}_${new Date().toISOString().slice(0,10)}.pdf`;
                        
                        download(blob, fileName, 'application/pdf');
                        
                    } catch (error) {
                        console.error('Error general:', error);
                        alert('Error al combinar PDFs: ' + error.message);
                    } finally {
                        loadingIndicator.style.display = 'none';
                        progressBar.style.width = '0%';
                    }
                });
            }
        });
    </script>
</body>
</html>