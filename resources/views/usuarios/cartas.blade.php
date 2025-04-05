<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cartas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0a58ca;
            --secondary-blue: #084298;
            --accent-orange: #fd7e14;
            --light-orange: #ffc107;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 6px 10px rgba(0,0,0,0.08);
            border: none;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-bottom: none;
        }
        .badge-status {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .btn-action {
            margin-right: 5px;
            border-radius: 8px;
            font-weight: 500;
        }
        .alert {
            margin-top: 20px;
            border-radius: 10px;
            border-left: 5px solid var(--accent-orange);
        }
        .file-info {
            background-color: #f1f8ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-orange);
        }
        .upload-section {
            border: 2px dashed #b3d1ff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            background-color: #f8fbff;
            transition: all 0.3s ease;
        }
        .upload-section:hover {
            border-color: var(--primary-blue);
            background-color: #e7f1ff;
        }
        .decision-buttons {
            margin-top: 20px;
        }
        .comentario-rechazo {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background-color: #fff9f2;
            border-radius: 8px;
            border: 1px solid #ffe8cc;
        }
        .btn-decision {
            min-width: 120px;
            font-weight: 500;
        }
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
        }
        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-warning {
            background-color: var(--light-orange);
            border-color: var(--light-orange);
            color: #212529;
        }
        .btn-warning:hover {
            background-color: var(--accent-orange);
            border-color: var(--accent-orange);
        }
        .document-section {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background-color: #fff;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .document-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .text-orange {
            color: var(--accent-orange);
        }
        .bg-orange-light {
            background-color: #fff4e6;
        }
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(10, 88, 202, 0.1);
            margin-right: 10px;
        }
        .icon-circle i {
            color: var(--primary-blue);
        }
        .title-with-icon {
            display: flex;
            align-items: center;
        }
        .drag-drop-text {
            color: var(--primary-blue);
            font-weight: 500;
        }
        .file-size-text {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 10px;
        }
        .badge-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="title-with-icon">
                <div class="icon-circle">
                    <i class="fas fa-envelope"></i>
                </div>
                <h1 class="mb-0">Mis Cartas</h1>
            </div>
            <a href="{{ route('menu') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Volver al Menú
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Carta de Aceptación -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-white">
                        <i class="fas fa-file-signature me-2"></i> Carta de Aceptación
                    </div>
                    <div class="card-body">
                        @if($carta->carta_aceptacion)
                            <div class="file-info">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        Documento subido
                                    </span>
                                    <span class="status-badge {{ $carta->estado_aceptacion == 'aprobado' ? 'badge-approved' : ($carta->estado_aceptacion == 'rechazado' ? 'badge-rejected' : 'badge-pending') }}">
                                        {{ $carta->estado_aceptacion == 'pendiente' ? 'Pendiente' : ucfirst($carta->estado_aceptacion) }}
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ Storage::url($carta->carta_aceptacion) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    <a href="{{ Storage::url($carta->carta_aceptacion) }}" 
                                       download 
                                       class="btn btn-sm btn-success me-2">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                    <span class="ms-auto align-self-center file-size-text">
                                        <i class="fas fa-info-circle me-1"></i> PDF
                                    </span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('cartas.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tipo" value="aceptacion">
                            <div class="upload-section">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: var(--primary-blue);"></i>
                                <p class="drag-drop-text mb-2">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                <input type="file" name="documento" class="d-none" id="file-aceptacion" accept=".pdf" required>
                                <label for="file-aceptacion" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> Seleccionar PDF
                                </label>
                                <div class="mt-2 file-size-text">
                                    <i class="fas fa-info-circle me-1"></i> Tamaño máximo: 2MB
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> {{ $carta->carta_aceptacion ? 'Actualizar' : 'Guardar' }} Documento
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Carta de Presentación -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-white">
                        <i class="fas fa-file-contract me-2"></i> Carta de Presentación
                    </div>
                    <div class="card-body">
                        @if($carta->carta_presentacion)
                            <div class="file-info">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        Documento subido
                                    </span>
                                    <span class="status-badge {{ $carta->estado_presentacion == 'aprobado' ? 'badge-approved' : ($carta->estado_presentacion == 'rechazado' ? 'badge-rejected' : 'badge-pending') }}">
                                        {{ $carta->estado_presentacion == 'pendiente' ? 'Pendiente' : ucfirst($carta->estado_presentacion) }}
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ Storage::url($carta->carta_presentacion) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    <a href="{{ Storage::url($carta->carta_presentacion) }}" 
                                       download 
                                       class="btn btn-sm btn-success me-2">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                    <span class="ms-auto align-self-center file-size-text">
                                        <i class="fas fa-info-circle me-1"></i> PDF
                                    </span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('cartas.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tipo" value="presentacion">
                            <div class="upload-section">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: var(--primary-blue);"></i>
                                <p class="drag-drop-text mb-2">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                <input type="file" name="documento" class="d-none" id="file-presentacion" accept=".pdf" required>
                                <label for="file-presentacion" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> Seleccionar PDF
                                </label>
                                <div class="mt-2 file-size-text">
                                    <i class="fas fa-info-circle me-1"></i> Tamaño máximo: 2MB
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> {{ $carta->carta_presentacion ? 'Actualizar' : 'Guardar' }} Documento
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Respuestas del Administrador -->
        @if($carta->respuesta_aceptacion || $carta->respuesta_presentacion || $carta->comentario_aceptacion || $carta->comentario_presentacion)
            <div class="card mt-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));">
                    <i class="fas fa-reply-all me-2"></i> Respuestas del Administrador
                </div>
                <div class="card-body">
                    @if($carta->respuesta_aceptacion)
                        <div class="document-section">
                            <h5 class="text-orange"><i class="fas fa-file-signature me-2"></i> Respuesta Carta de Aceptación</h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="badge bg-{{ $carta->estado_aceptacion == 'aprobado' ? 'success' : ($carta->estado_aceptacion == 'rechazado' ? 'danger' : 'warning') }} badge-status">
                                        {{ $carta->estado_aceptacion == 'pendiente' ? 'Pendiente' : ucfirst($carta->estado_aceptacion) }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ Storage::url($carta->respuesta_aceptacion) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-eye me-1"></i> Ver PDF
                                    </a>
                                    <a href="{{ Storage::url($carta->respuesta_aceptacion) }}" 
                                       download 
                                       class="btn btn-sm btn-success me-2">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                </div>
                            </div>
                            
                            @if($carta->estado_aceptacion == 'pendiente')
                                <div class="decision-buttons mt-3">
                                    <form action="{{ route('admin.cartas.responder', $carta->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="tipo" value="aceptacion">
                                        <input type="hidden" name="accion" value="aprobado">
                                        <button type="submit" class="btn btn-success btn-decision me-2">
                                            <i class="fas fa-check me-1"></i> Aprobar
                                        </button>
                                    </form>
                                    
                                    <button class="btn btn-danger btn-decision" id="btn-rechazar-aceptacion">
                                        <i class="fas fa-times me-1"></i> Rechazar
                                    </button>
                                    
                                    <form action="{{ route('admin.cartas.responder', $carta->id) }}" method="POST" id="form-rechazo-aceptacion" class="comentario-rechazo mt-3">
                                        @csrf
                                        <input type="hidden" name="tipo" value="aceptacion">
                                        <input type="hidden" name="accion" value="rechazado">
                                        <div class="mb-3">
                                            <label for="comentario-aceptacion" class="form-label fw-bold">Motivo del rechazo:</label>
                                            <textarea name="comentario" id="comentario-aceptacion" class="form-control" rows="3" required placeholder="Por favor, explica el motivo del rechazo..."></textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-outline-secondary me-2" id="cancelar-rechazo-aceptacion">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-paper-plane me-1"></i> Confirmar Rechazo
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($carta->respuesta_presentacion)
                        <div class="document-section">
                            <h5 class="text-orange"><i class="fas fa-file-contract me-2"></i> Respuesta Carta de Presentación</h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="badge bg-{{ $carta->estado_presentacion == 'aprobado' ? 'success' : ($carta->estado_presentacion == 'rechazado' ? 'danger' : 'warning') }} badge-status">
                                        {{ $carta->estado_presentacion == 'pendiente' ? 'Pendiente' : ucfirst($carta->estado_presentacion) }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ Storage::url($carta->respuesta_presentacion) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-eye me-1"></i> Ver PDF
                                    </a>
                                    <a href="{{ Storage::url($carta->respuesta_presentacion) }}" 
                                       download 
                                       class="btn btn-sm btn-success me-2">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                </div>
                            </div>
                            
                            @if($carta->estado_presentacion == 'pendiente')
                                <div class="decision-buttons mt-3">
                                    <form action="{{ route('admin.cartas.responder', $carta->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="tipo" value="presentacion">
                                        <input type="hidden" name="accion" value="aprobado">
                                        <button type="submit" class="btn btn-success btn-decision me-2">
                                            <i class="fas fa-check me-1"></i> Aprobar
                                        </button>
                                    </form>
                                    
                                    <button class="btn btn-danger btn-decision" id="btn-rechazar-presentacion">
                                        <i class="fas fa-times me-1"></i> Rechazar
                                    </button>
                                    
                                    <form action="{{ route('admin.cartas.responder', $carta->id) }}" method="POST" id="form-rechazo-presentacion" class="comentario-rechazo mt-3">
                                        @csrf
                                        <input type="hidden" name="tipo" value="presentacion">
                                        <input type="hidden" name="accion" value="rechazado">
                                        <div class="mb-3">
                                            <label for="comentario-presentacion" class="form-label fw-bold">Motivo del rechazo:</label>
                                            <textarea name="comentario" id="comentario-presentacion" class="form-control" rows="3" required placeholder="Por favor, explica el motivo del rechazo..."></textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-outline-secondary me-2" id="cancelar-rechazo-presentacion">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-paper-plane me-1"></i> Confirmar Rechazo
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($carta->comentario_aceptacion || $carta->comentario_presentacion)
                        <div class="alert bg-orange-light mt-3">
                            <h5 class="text-orange"><i class="fas fa-comment-dots me-2"></i>Comentarios:</h5>
                            @if($carta->comentario_aceptacion)
                                <p><strong class="text-orange">Aceptación:</strong> {{ $carta->comentario_aceptacion }}</p>
                            @endif
                            @if($carta->comentario_presentacion)
                                <p class="mb-0"><strong class="text-orange">Presentación:</strong> {{ $carta->comentario_presentacion }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script>
        // Mostrar nombre de archivo seleccionado
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const label = this.previousElementSibling.querySelector('p');
                if(this.files.length > 0) {
                    label.innerHTML = `<i class="fas fa-file-pdf text-danger me-1"></i> ${this.files[0].name}`;
                }
            });
        });

        // Funcionalidad para mostrar/ocultar formulario de rechazo
        document.getElementById('btn-rechazar-aceptacion')?.addEventListener('click', function() {
            document.getElementById('form-rechazo-aceptacion').style.display = 'block';
        });
        
        document.getElementById('btn-rechazar-presentacion')?.addEventListener('click', function() {
            document.getElementById('form-rechazo-presentacion').style.display = 'block';
        });

        // Funcionalidad para cancelar rechazo
        document.getElementById('cancelar-rechazo-aceptacion')?.addEventListener('click', function() {
            document.getElementById('form-rechazo-aceptacion').style.display = 'none';
            document.getElementById('comentario-aceptacion').value = '';
        });
        
        document.getElementById('cancelar-rechazo-presentacion')?.addEventListener('click', function() {
            document.getElementById('form-rechazo-presentacion').style.display = 'none';
            document.getElementById('comentario-presentacion').value = '';
        });
    </script>
</body>
</html>