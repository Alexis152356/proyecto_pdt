<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Definición del charset y viewport para asegurar la correcta visualización en dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Título de la página -->
    <title>Subir Documentos</title>
    
    <!-- Inclusión de Bootstrap 5 para el diseño responsivo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Estilos para el fondo de la página */
        body {
            background-color: #f8f9fa; /* Fondo gris claro */
        }

        /* Estilo para cada tarjeta de documento */
        .document-card {
            background-color: #dcdcdc; /* Fondo gris para cada tarjeta */
            padding: 15px;
            border-left: 10px solid orange; /* Borde izquierdo de color naranja */
            margin-bottom: 20px;
            border-radius: 5px; /* Bordes redondeados */
        }

        /* Estilos según el estado del documento (pendiente, aprobado, rechazado) */
        .document-card.pendiente {
            border-left-color: #ffc107; /* Color de borde para estado pendiente (amarillo) */
        }
        .document-card.aprobado {
            border-left-color: #28a745; /* Color de borde para estado aprobado (verde) */
        }
        .document-card.rechazado {
            border-left-color: #dc3545; /* Color de borde para estado rechazado (rojo) */
        }

        /* Estilos para la insignia de estado del documento */
        .badge-estado {
            font-size: 0.9rem;
            padding: 5px 10px;
            font-weight: bold;
        }

        /* Estilos para la caja de comentarios del administrador cuando el documento es rechazado */
        .comentario-box {
            background-color: #fff3cd; /* Fondo amarillo claro */
            border-left: 5px solid #ffc107; /* Borde izquierdo amarillo */
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }

        /* Estilos para las acciones de los documentos (ver, eliminar) */
        .document-actions {
            margin-top: 10px;
        }

        /* Botón de subida de documentos con color personalizado */
        .btn-subir {
            background-color: navy; /* Fondo azul oscuro */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Contenedor principal para la página -->
    <div class="container py-5">
        <!-- Título de la página con un icono de carpeta -->
        <h1 class="mb-4"><img src="{{ asset('folder-icon.png') }}" width="50"> Subir Documentos Requeridos</h1>

        <!-- Mensaje de éxito si se ha subido un documento correctamente -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filas de documentos para cada tipo de documento -->
        <div class="row">
            <!-- Se itera sobre los tipos de documentos (tiposDocumentos) para mostrar la tarjeta correspondiente -->
            @foreach($tiposDocumentos as $tipo => $nombre)
            <div class="col-md-6 mb-4">
                <!-- Tarjeta de documento con clase dinámica según el estado del documento -->
                <div class="document-card {{ isset($documentos[$tipo]) ? $documentos[$tipo]->estado : 'pendiente' }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <!-- Título del tipo de documento -->
                        <h4>{{ $nombre }}</h4>
                        
                        <!-- Si el documento ha sido subido, muestra su estado -->
                        @if(isset($documentos[$tipo]))
                            <span class="badge badge-estado bg-{{ 
                                $documentos[$tipo]->estado == 'aprobado' ? 'success' : 
                                ($documentos[$tipo]->estado == 'rechazado' ? 'danger' : 'warning') 
                            }}">
                                {{ ucfirst($documentos[$tipo]->estado) }}
                            </span>
                        @endif
                    </div>

                    <!-- Si el documento ha sido subido, se muestran más detalles -->
                    @if(isset($documentos[$tipo]))
                        <p class="small text-muted">
                            Subido el {{ $documentos[$tipo]->created_at->format('d/m/Y H:i') }}<br>
                            @if($documentos[$tipo]->revisado_at)
                                Revisado el {{ $documentos[$tipo]->revisado_at->format('d/m/Y H:i') }}
                            @endif
                        </p>

                        <!-- Si el documento fue rechazado, se muestra el comentario del administrador -->
                        @if($documentos[$tipo]->estado == 'rechazado' && $documentos[$tipo]->comentario)
                        <div class="comentario-box">
                            <strong>Comentario del administrador:</strong>
                            <p class="mb-0">{{ $documentos[$tipo]->comentario }}</p>
                        </div>
                        @endif

                        <!-- Acciones para el documento (ver y eliminar) -->
                        <div class="document-actions">
                            <a href="{{ route('documentos.show', $documentos[$tipo]->id) }}" target="_blank" class="btn btn-sm btn-primary me-2">
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
                    @else
                        <!-- Formulario para subir un nuevo documento -->
                        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                            <div class="mb-3">
                                <input type="file" name="documento" class="form-control" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn-subir">
                                <i class="fas fa-upload me-1"></i> Subir Documento
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Inclusión de FontAwesome para los íconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <!-- Inclusión de Bootstrap JS para la interactividad -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
