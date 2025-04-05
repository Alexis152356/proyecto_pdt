<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar Cartas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --azul-fuerte: #0a58ca;
            --azul-hover: #084298;
            --naranja: #fd7e14;
            --naranja-hover: #e67312;
            --naranja-claro: #ffe5d0;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card-container {
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
            background-color: white;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(10, 88, 202, 0.15);
        }
        .card-header {
            background-color: var(--azul-fuerte);
            color: white;
            padding: 18px 25px;
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 3px solid var(--naranja);
        }
        .document-section {
            padding: 25px;
            border-bottom: 1px solid #e0e0e0;
        }
        .document-section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 1.25rem;
            margin-bottom: 18px;
            color: var(--azul-fuerte);
            display: flex;
            align-items: center;
        }
        .section-title i {
            margin-right: 12px;
            color: var(--naranja);
        }
        .document-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
            background-color: #fff;
        }
        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(10, 88, 202, 0.1);
            border-color: var(--azul-fuerte);
        }
        .document-actions {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .btn-outline-primary {
            color: var(--azul-fuerte);
            border-color: var(--azul-fuerte);
        }
        .btn-outline-primary:hover {
            background-color: var(--azul-fuerte);
            color: white;
        }
        .btn-outline-success {
            color: #198754;
            border-color: #198754;
        }
        .btn-outline-success:hover {
            background-color: #198754;
            color: white;
        }
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        .upload-box {
            border: 2px dashed #ddd;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            background-color: #f8f9fa;
            margin-top: 15px;
            transition: all 0.3s;
        }
        .upload-box:hover {
            border-color: var(--naranja);
            background-color: var(--naranja-claro);
        }
        .file-info {
            margin-top: 12px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            margin-bottom: 12px;
            display: inline-flex;
            align-items: center;
            border-radius: 20px;
        }
        .document-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .document-title i {
            margin-right: 12px;
            font-size: 1.2rem;
            color: var(--azul-fuerte);
        }
        .btn-primary {
            background-color: var(--azul-fuerte);
            border-color: var(--azul-fuerte);
        }
        .btn-primary:hover {
            background-color: var(--azul-hover);
            border-color: var(--azul-hover);
        }
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }
        .alert-warning {
            background-color: var(--naranja-claro);
            border-color: #ffdfb9;
            color: #664d03;
        }
        h2 {
            color: var(--azul-fuerte);
            font-weight: 600;
            border-bottom: 2px solid var(--naranja);
            padding-bottom: 10px;
            display: inline-block;
        }
        .badge.bg-warning {
            background-color: var(--naranja) !important;
        }
        .badge.bg-success {
            background-color: #198754 !important;
        }
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-envelope me-2"></i>Revisar Cartas</h2>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse($cartas as $carta)
            <div class="card-container">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Cartas de {{ $carta->user ? $carta->user->name : 'Usuario no disponible' }}
                </div>
                
                <!-- Sección 1: Documentos recibidos del usuario -->
                <div class="document-section">
                    <h4 class="section-title"><i class="fas fa-paperclip"></i>Documentos Recibidos</h4>
                    
                    <div class="row g-4">
                        <!-- Carta de Aceptación -->
                        <div class="col-md-6">
                            <div class="document-card h-100">
                                <div class="document-title">
                                    <i class="fas fa-file-signature"></i>
                                    <h5 class="mb-0">Carta de Aceptación</h5>
                                </div>
                                <div class="document-actions">
                                    <a href="{{ asset('storage/' . $carta->carta_aceptacion) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </a>
                                    <a href="{{ asset('storage/' . $carta->carta_aceptacion) }}" download class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carta de Presentación -->
                        <div class="col-md-6">
                            <div class="document-card h-100">
                                <div class="document-title">
                                    <i class="fas fa-file-contract"></i>
                                    <h5 class="mb-0">Carta de Presentación</h5>
                                </div>
                                <div class="document-actions">
                                    <a href="{{ asset('storage/' . $carta->carta_presentacion) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </a>
                                    <a href="{{ asset('storage/' . $carta->carta_presentacion) }}" download class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección 2: Respuestas subidas (si existen) -->
                @if($carta->respuesta_aceptacion || $carta->respuesta_presentacion)
                <div class="document-section">
                    <h4 class="section-title"><i class="fas fa-reply"></i>Respuestas Enviadas</h4>
                    
                    <div class="row g-4">
                        @if($carta->respuesta_aceptacion)
                        <div class="col-md-6">
                            <div class="document-card h-100">
                                <div class="document-title">
                                    <i class="fas fa-file-signature"></i>
                                    <h5 class="mb-0">Respuesta Aceptación</h5>
                                </div>
                                
                                <!-- Estado de aprobación/rechazo -->
                                <div class="mb-3">
                                    <span class="badge bg-{{ $carta->estado_aceptacion == 'aprobado' ? 'success' : ($carta->estado_aceptacion == 'rechazado' ? 'danger' : 'warning') }} status-badge">
                                        <i class="fas fa-{{ $carta->estado_aceptacion == 'aprobado' ? 'check-circle' : ($carta->estado_aceptacion == 'rechazado' ? 'times-circle' : 'clock') }} me-1"></i>
                                        {{ ucfirst($carta->estado_aceptacion) }}
                                    </span>
                                    @if($carta->comentario_aceptacion && $carta->estado_aceptacion == 'rechazado')
                                        <div class="mt-2 alert alert-warning p-2 small">
                                            <strong><i class="fas fa-comment me-1"></i>Motivo:</strong> {{ $carta->comentario_aceptacion }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="document-actions">
                                    <a href="{{ asset('storage/'.$carta->respuesta_aceptacion) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </a>
                                    <a href="{{ asset('storage/'.$carta->respuesta_aceptacion) }}" download class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                    <form action="{{ route('admin.cartas.eliminar-respuesta', ['id' => $carta->id, 'tipo' => 'aceptacion']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                                <div class="file-info text-muted small">
                                    <i class="fas fa-calendar-alt me-1"></i> Subido el {{ \Carbon\Carbon::parse($carta->updated_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($carta->respuesta_presentacion)
                        <div class="col-md-6">
                            <div class="document-card h-100">
                                <div class="document-title">
                                    <i class="fas fa-file-contract"></i>
                                    <h5 class="mb-0">Respuesta Presentación</h5>
                                </div>
                                
                                <!-- Estado de aprobación/rechazo -->
                                <div class="mb-3">
                                    <span class="badge bg-{{ $carta->estado_presentacion == 'aprobado' ? 'success' : ($carta->estado_presentacion == 'rechazado' ? 'danger' : 'warning') }} status-badge">
                                        <i class="fas fa-{{ $carta->estado_presentacion == 'aprobado' ? 'check-circle' : ($carta->estado_presentacion == 'rechazado' ? 'times-circle' : 'clock') }} me-1"></i>
                                        {{ ucfirst($carta->estado_presentacion) }}
                                    </span>
                                    @if($carta->comentario_presentacion && $carta->estado_presentacion == 'rechazado')
                                        <div class="mt-2 alert alert-warning p-2 small">
                                            <strong><i class="fas fa-comment me-1"></i>Motivo:</strong> {{ $carta->comentario_presentacion }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="document-actions">
                                    <a href="{{ asset('storage/respuestas/' . basename($carta->respuesta_presentacion)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </a>
                                    <a href="{{ asset('storage/respuestas/' . basename($carta->respuesta_presentacion)) }}" download class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                    <form action="{{ route('admin.cartas.eliminar-respuesta', ['id' => $carta->id, 'tipo' => 'presentacion']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                                <div class="file-info text-muted small">
                                    <i class="fas fa-calendar-alt me-1"></i> Subido el {{ \Carbon\Carbon::parse($carta->updated_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Sección 3: Subir respuestas (si faltan) -->
                @if(!$carta->respuesta_aceptacion || !$carta->respuesta_presentacion)
                <div class="document-section">
                    <h4 class="section-title"><i class="fas fa-cloud-upload-alt"></i>Subir Respuestas</h4>
                    
                    <div class="row g-4">
                        @if(!$carta->respuesta_aceptacion)
                        <div class="col-md-6">
                            <div class="document-card h-100">
                                <form action="{{ route('admin.cartas.subir-respuesta', ['id' => $carta->id, 'tipo' => 'aceptacion']) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="document-title">
                                        <i class="fas fa-file-signature"></i>
                                        <h5 class="mb-0">Respuesta Aceptación</h5>
                                    </div>
                                    <div class="upload-box">
                                        <input type="file" name="documento" class="d-none" id="file-aceptacion-{{ $carta->id }}" accept=".pdf" required>
                                        <label for="file-aceptacion-{{ $carta->id }}" class="btn btn-primary mb-3">
                                            <i class="fas fa-upload me-1"></i> Seleccionar PDF
                                        </label>
                                        <div id="file-info-aceptacion-{{ $carta->id }}" class="file-info d-none">
                                            <i class="fas fa-file-pdf me-1"></i> <span id="file-name-aceptacion-{{ $carta->id }}"></span>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100 mt-2">
                                            <i class="fas fa-save me-1"></i> Subir Documento
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$carta->respuesta_presentacion)
                        <div class="col-md-6">
                            <div class="document-card h-100">
                                <form action="{{ route('admin.cartas.subir-respuesta', ['id' => $carta->id, 'tipo' => 'presentacion']) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="document-title">
                                        <i class="fas fa-file-contract"></i>
                                        <h5 class="mb-0">Respuesta Presentación</h5>
                                    </div>
                                    <div class="upload-box">
                                        <input type="file" name="documento" class="d-none" id="file-presentacion-{{ $carta->id }}" accept=".pdf" required>
                                        <label for="file-presentacion-{{ $carta->id }}" class="btn btn-primary mb-3">
                                            <i class="fas fa-upload me-1"></i> Seleccionar PDF
                                        </label>
                                        <div id="file-info-presentacion-{{ $carta->id }}" class="file-info d-none">
                                            <i class="fas fa-file-pdf me-1"></i> <span id="file-name-presentacion-{{ $carta->id }}"></span>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100 mt-2">
                                            <i class="fas fa-save me-1"></i> Subir Documento
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        @empty
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>No hay cartas para revisar en este momento.
            </div>
        @endforelse
    </div>

    <!-- Bootstrap JS Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script>
        // Mostrar nombre de archivo seleccionado
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const id = this.id.split('-').pop();
                const fileInfo = document.getElementById('file-info-' + this.id.replace('file-', ''));
                const fileName = document.getElementById('file-name-' + this.id.replace('file-', ''));
                
                if(this.files.length > 0) {
                    fileInfo.classList.remove('d-none');
                    fileName.textContent = this.files[0].name;
                } else {
                    fileInfo.classList.add('d-none');
                }
            });
        });

        // Confirmación antes de eliminar
        document.querySelectorAll('form[action*="eliminar-respuesta"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if(!confirm('¿Está seguro que desea eliminar esta respuesta?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>