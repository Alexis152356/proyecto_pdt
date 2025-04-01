<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .document-card {
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
        .document-actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Subir Documentos Requeridos</h1>
        
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="row">
            @foreach($tiposDocumentos as $tipo => $nombre)
            <div class="col-md-6 mb-4">
                <div class="card document-card {{ isset($documentos[$tipo]) ? $documentos[$tipo]->estado : 'pendiente' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title">{{ $nombre }}</h5>
                            @if(isset($documentos[$tipo]))
                                <span class="badge badge-estado bg-{{ 
                                    $documentos[$tipo]->estado == 'aprobado' ? 'success' : 
                                    ($documentos[$tipo]->estado == 'rechazado' ? 'danger' : 'warning') 
                                }}">
                                    {{ ucfirst($documentos[$tipo]->estado) }}
                                </span>
                            @endif
                        </div>
                        
                        @if(isset($documentos[$tipo]))
                            <div class="mt-2">
                                <p class="small text-muted">
                                    Subido el {{ $documentos[$tipo]->created_at->format('d/m/Y H:i') }}
                                    @if($documentos[$tipo]->revisado_at)
                                        <br>Revisado el {{ $documentos[$tipo]->revisado_at->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                                
                                @if($documentos[$tipo]->estado == 'rechazado' && $documentos[$tipo]->comentario)
                                <div class="comentario-box">
                                    <strong>Comentario del administrador:</strong>
                                    <p>{{ $documentos[$tipo]->comentario }}</p>
                                </div>
                                @endif
                                
                                <div class="document-actions">
                                    <a href="{{ route('documentos.show', $documentos[$tipo]->id) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary me-2">
                                       <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    <form action="{{ route('documentos.destroy', $documentos[$tipo]->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <input type="hidden" name="tipo" value="{{ $tipo }}">
                                <div class="mb-3">
                                    <input type="file" name="documento" class="form-control" accept=".pdf" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> Subir Documento
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>