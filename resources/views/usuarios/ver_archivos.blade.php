<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-bg: #f8f9fa;
            --card-border: #e9ecef;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #0d1b63, #0d6efd);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .document-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary-color);
            background-color: white;
        }
        
        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-left-color: var(--success-color);
        }
        
        .document-card.rejected {
            border-left-color: var(--danger-color);
        }
        
        .document-card .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .badge-document {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        .empty-state i {
            font-size: 3.5rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        
        .file-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--light-bg);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        
        .file-meta .badge {
            font-weight: 500;
        }
        
        .action-buttons .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            min-width: 100px;
        }
        
        .comment-box {
            background-color: #fff9e6;
            border-left: 3px solid var(--warning-color);
            border-radius: 0 6px 6px 0;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .comment-box small {
            font-weight: 600;
            color: #856404;
        }
        
        .comment-box p {
            margin-bottom: 0;
            color: #856404;
        }
        
        .back-btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
        
        .page-title {
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .page-title i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="header-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title mb-0">
                    <i class="fas fa-file-alt"></i>Mis Documentos
                </h1>
                <a href="{{ route('usuario.dashboard') }}" class="btn btn-light back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <div class="container py-4">
        @if($documentos->isEmpty())
            <div class="card border-0">
                <div class="card-body empty-state">
                    <i class="far fa-folder-open"></i>
                    <h3 class="h5 mb-2">No hay documentos disponibles</h3>
                    <p class="text-muted mb-0">Aún no se han cargado documentos en tu perfil</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($documentos as $documento)
                <div class="col-lg-6">
                    <div class="card document-card @if($documento->estado == 'rechazado') rejected @endif">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    {{ $tiposDocumentos[$documento->tipo] ?? $documento->tipo }}
                                </h5>
                                @if($documento->estado != 'pendiente')
                                <span class="badge bg-{{ $documento->estado == 'aprobado' ? 'success' : 'danger' }} badge-document">
                                    {{ ucfirst($documento->estado) }}
                                </span>
                                @endif
                            </div>

                            <div class="file-meta">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $documento->created_at->format('d/m/Y H:i') }}
                                </small>
                                <span class="badge bg-light text-dark">
                                    @if(Storage::disk('public')->exists($documento->ruta))
                                        {{ round(Storage::disk('public')->size($documento->ruta) / 1024 ) }} KB
                                    @else
                                        <i class="fas fa-exclamation-triangle text-warning me-1"></i>No disponible
                                    @endif
                                </span>
                            </div>

                            @if($documento->estado == 'rechazado' && $documento->comentario)
                                <div class="comment-box">
                                    <small class="fw-bold">Comentario del administrador:</small>
                                    <p class="mb-0 small">{{ $documento->comentario }}</p>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between action-buttons">
                                <a href="{{ route('archivos.show', $documento->id) }}" 
                                   target="_blank"
                                   class="btn btn-outline-primary">
                                    <i class="far fa-eye me-1"></i>Ver
                                </a>
                                <a href="{{ route('archivos.show', $documento->id) }}?download=1" 
                                   class="btn btn-outline-success">
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