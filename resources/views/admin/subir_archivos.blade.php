<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Documentos - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #0D1B63;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .header {
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }
        .container-content {
            background-color: #D3D3D3;
            padding: 50px;
            position: relative;
            min-height: calc(100vh - 120px);
        }
        .document-card {
            background-color: #FF6600;
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            height: 100%;
        }
        .custom-document-card {
            background-color: #28a745;
        }
        .document-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
        .btn-white {
            background-color: white;
            color: #FF6600;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-white:hover {
            background-color: #f0f0f0;
        }
        .image-container {
            position: absolute;
            bottom: 20px;
            right: 50px;
        }
        .footer {
            background-color: #0D1B63;
            height: 50px;
        }
        .add-new-section {
            margin-top: 40px;
            text-align: center;
            padding: 20px 0;
        }
        .new-document-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
            display: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-control {
            margin-bottom: 15px;
        }
        .document-upload {
            text-align: center;
            padding: 15px 0;
        }
        #add-new-btn {
            padding: 12px 25px;
            font-size: 1.1rem;
        }
        #create-document-btn {
            width: 100%;
            padding: 12px;
        }
        .badge {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="/img/logo-blue.svg" alt="Logo">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <div class="container-content">
        <h1 class="mb-4">Gestión de Documentos</h1>

        <div class="row" id="documents-container">
            @foreach($tiposDocumentos as $clave => $nombre)
            @php
                $documento = $documentos[$clave] ?? null;
            @endphp
            <div class="col-md-6 mb-4">
                <div class="document-card @if($documento && $documento->es_custom) custom-document-card @endif">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title mb-0">{{ $nombre }}</h5>
                        @if($documento)
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-check me-1"></i>Subido
                            </span>
                        @endif
                    </div>
                    
                    @if($documento)
                        <div class="mt-3 d-flex justify-content-between">
                            <a href="{{ route('archivos.show', $documento->id) }}" 
                               target="_blank"
                               class="btn btn-white me-2">
                                <i class="far fa-eye me-1"></i> Ver
                            </a>
                            <form action="{{ route('archivos.destroy', $documento->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  id="delete-form-{{ $documento->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="btn btn-white delete-btn"
                                        data-id="{{ $documento->id }}">
                                    <i class="far fa-trash-alt me-1"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('archivos.store') }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $clave }}">
                            <div class="document-upload">
                                <i class="fas fa-cloud-upload-alt fa-3x text-white mb-3"></i>
                                <p class="mb-3">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                <input type="file" name="documento" class="d-none" id="file-{{ Str::slug($clave) }}" accept=".pdf" required>
                                <label for="file-{{ Str::slug($clave) }}" class="btn btn-white mb-3">
                                    <i class="fas fa-upload me-1"></i>Seleccionar PDF
                                </label>
                                <button type="submit" class="btn btn-white w-100">
                                    <i class="fas fa-save me-1"></i>Guardar Documento
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Sección para agregar nuevos tipos de documentos -->
        <div class="add-new-section">
            <button id="add-new-btn" class="btn btn-white">
                <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Tipo de Documento
            </button>
            
            <div id="new-document-form" class="new-document-form">
                <h4 class="mb-4">Nuevo Tipo de Documento</h4>
                <form id="create-document-type-form" action="{{ route('admin.add-document-type') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="new-document-title" class="form-label">Nombre del Documento</label>
                        <input type="text" class="form-control" id="new-document-title" name="nombre" 
                               placeholder="Ej: Certificado de Estudios" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-document-key" class="form-label">Clave Interna (sin espacios, mayúsculas)</label>
                        <input type="text" class="form-control" id="new-document-key" name="clave" 
                               placeholder="Ej: CERTIFICADO_ESTUDIOS" required>
                    </div>
                    <button type="submit" class="btn btn-white">
                        <i class="fas fa-save me-2"></i>Crear Documento
                    </button>
                </form>
            </div>
        </div>

        <!-- Imagen decorativa -->
        <div class="image-container">
            <img src="/img/image5.png" alt="Imagen decorativa" width="200">
        </div>
    </div>

    <div class="footer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para eliminar documentos
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('¿Estás seguro de eliminar este documento?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });

        // Mostrar/ocultar formulario para nuevo tipo
        document.getElementById('add-new-btn').addEventListener('click', function() {
            const form = document.getElementById('new-document-form');
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        });

        // Convertir clave a mayúsculas automáticamente
        document.getElementById('new-document-key').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/\s+/g, '_');
        });

        // Enviar formulario via AJAX
        document.getElementById('create-document-type-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la página para mostrar los cambios
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al crear el tipo de documento');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
            });
        });
    </script>
</body>
</html>