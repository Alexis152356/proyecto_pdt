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
        }
        .document-card {
            background-color: #FF6600; /* Naranja */
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
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
        <h1>Gestión de Documentos</h1>

        <div class="row">
            @foreach([
                'PERFIL DE PUESTO TECNÓLOGO' => 'Perfil de Puesto Tecnólogo',
                'GENERALIDADES DEL PROGRAMA DE PDT' => 'Generalidades del Programa de PDT',
                'LISTA DE DOCUMENTOS UPPER' => 'Lista de Documentos UPPER',
                'CONDUCTAS EN ALMACÉN' => 'Conductas en Almacén',
                'FORMATO DE ESTUDIO SOCIOECONOMICO SOLGISTIKA' => 'Formato de Estudio Socioeconómico Solgistika',
                'TRAMITE EN LINEA' => 'Trámite en Línea',
                'FOTOS' => 'Fotos',
                'Ficha de datos para dar de alta' => 'Ficha de Datos para Alta'
            ] as $tipo => $nombre)
            <div class="col-md-6">
                <div class="document-card">
                    <h5 class="card-title">{{ $nombre }}</h5>
                    @if(isset($documentos[$tipo]))
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-check me-1"></i>Subido
                        </span>
                        <div class="mt-3">
                            <a href="{{ route('archivos.show', $documentos[$tipo]->id) }}" 
                               target="_blank"
                               class="btn btn-white">
                                <i class="far fa-eye"></i> Ver
                            </a>
                            <form action="{{ route('archivos.destroy', $documentos[$tipo]->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  id="delete-form-{{ $documentos[$tipo]->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="btn btn-white delete-btn"
                                        data-id="{{ $documentos[$tipo]->id }}">
                                    <i class="far fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('archivos.store') }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                            <div class="document-upload">
                                <i class="fas fa-cloud-upload-alt fa-3x text-white mb-3"></i>
                                <p class="mb-2">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                <input type="file" name="documento" class="d-none" id="file-{{ Str::slug($tipo) }}" accept=".pdf" required>
                                <label for="file-{{ Str::slug($tipo) }}" class="btn btn-white">
                                    <i class="fas fa-upload me-1"></i>Seleccionar PDF
                                </label>
                            </div>
                            <button type="submit" class="btn btn-white w-100 mt-2">
                                <i class="fas fa-save me-1"></i>Guardar Documento
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Imagen decorativa -->
        <div class="image-container">
            <img src="/img/image5.png" alt="Imagen decorativa" width="200">
        </div>
    </div>

    <div class="footer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('¿Estás seguro de eliminar este documento?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    </script>
</body>
</html>