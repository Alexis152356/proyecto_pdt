<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos de {{ $usuario->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>
                    <i class="fas fa-folder me-2"></i>
                    Documentos de {{ $usuario->nombre }}
                </h1>
                <p class="text-muted">{{ $usuario->correo }}</p>
            </div>
            <a href="{{ route('admin.listar.usuarios') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver a Usuarios
            </a>
        </div>

        @if($usuario->documentos->isEmpty())
            <div class="alert alert-info">
                Este usuario no ha subido documentos aún.
            </div>
        @else
            <div class="row">
                @foreach($usuario->documentos as $documento)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $tiposDocumentos[$documento->tipo] ?? $documento->tipo }}
                            </h5>
                            <p class="text-muted small">
                                Subido el {{ $documento->created_at->format('d/m/Y H:i') }}
                            </p>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('documentos.show', $documento->id) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i> Ver PDF
                                </a>
                                <a href="{{ route('documentos.show', $documento->id) }}?download=1" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i> Descargar
                                </a>
                            </div>
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