<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .document-card {
            border-left: 5px solid #dee2e6;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .document-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .badge-document {
            font-size: 0.8rem;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-file-pdf text-danger me-2"></i>Mis Documentos
            </h1>
            <a href="{{ route('usuario.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>

        @if($documentos->isEmpty())
            <div class="card">
                <div class="card-body empty-state">
                    <i class="far fa-folder-open fa-4x mb-3"></i>
                    <h3 class="h5">No hay documentos disponibles</h3>
                    <p class="mb-0">Aún no se han cargado documentos en tu perfil</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($documentos as $documento)
                <div class="col-md-6">
                    <div class="card document-card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">
                                    {{ $tiposDocumentos[$documento->tipo] ?? $documento->tipo }}
                                </h5>
                                <span class="badge bg-{{ $documento->estado == 'aprobado' ? 'success' : ($documento->estado == 'rechazado' ? 'danger' : 'warning') }} badge-document">
                                    {{ ucfirst($documento->estado) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $documento->created_at->format('d/m/Y H:i') }}
                                </small>
                                <span class="badge bg-light text-dark">
    @if(Storage::disk('public')->exists($documento->ruta))
        {{ round(Storage::disk('public')->size($documento->ruta) / 1024 ) }} KB
    @else
        Archivo no disponible
    @endif
</span>
                            </div>

                            @if($documento->estado == 'rechazado' && $documento->comentario)
                                <div class="alert alert-warning p-2 mb-3">
                                    <small class="fw-bold">Comentario:</small>
                                    <p class="mb-0 small">{{ $documento->comentario }}</p>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('archivos.show', $documento->id) }}" 
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="far fa-eye me-1"></i>Ver
                                </a>
                                <a href="{{ route('archivos.show', $documento->id) }}?download=1" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i>Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>