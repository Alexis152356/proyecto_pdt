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
                <a href="{{ route('admin.revisar.cartas', ['usuario_id' => $usuario->id]) }}" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i> Revisar Cartas
                </a>
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
</body>
</html>