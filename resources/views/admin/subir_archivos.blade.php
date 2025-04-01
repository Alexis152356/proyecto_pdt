<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Documentos - Admin</title>
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
        .document-upload {
            border: 2px dashed #dee2e6;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .document-upload:hover {
            border-color: #0d6efd;
        }
        .badge-document {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-file-pdf text-danger me-2"></i>Gestión de Documentos
            </h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>

        <div class="row">
            @foreach([
                'cv' => 'Currículum Vitae',
                'carta_invitacion' => 'Carta de Invitación',
                'acta_nacimiento' => 'Acta de Nacimiento', 
                'ine' => 'INE/Identificación',
                'curp' => 'CURP',
                'rfc' => 'RFC',
                'comprobante_domicilio' => 'Comprobante de Domicilio',
                'certificado_medico' => 'Certificado Médico'
            ] as $tipo => $nombre)
            <div class="col-md-6">
                <div class="card document-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">{{ $nombre }}</h5>
                            @if(isset($documentos[$tipo]))
                                <span class="badge bg-success badge-document">
                                    <i class="fas fa-check me-1"></i>Subido
                                </span>
                            @endif
                        </div>

                        @if(isset($documentos[$tipo]))
    <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">
            <i class="far fa-calendar me-1"></i>
            {{ $documentos[$tipo]->created_at->format('d/m/Y H:i') }}
        </small>
        <div>
            <!-- Botón para ver el PDF -->
            <a href="{{ route('archivos.show', $documentos[$tipo]->id) }}" 
               target="_blank"
               class="btn btn-sm btn-outline-primary me-1">
                <i class="far fa-eye"></i>
            </a>
            
            <form action="{{ route('archivos.destroy', $documentos[$tipo]->id) }}" 
      method="POST" 
      class="d-inline"
      id="delete-form-{{ $documentos[$tipo]->id }}">
    @csrf
    @method('DELETE')
    <button type="button" 
            class="btn btn-sm btn-outline-danger delete-btn"
            data-id="{{ $documentos[$tipo]->id }}">
        <i class="far fa-trash-alt"></i> Eliminar
    </button>
</form>
        </div>
    </div>
@else
    
                        <form action="{{ route('archivos.store') }}" 
                                  method="POST" 
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tipo" value="{{ $tipo }}">
                                <div class="document-upload">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="mb-2">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                    <input type="file" name="documento" class="d-none" id="file-{{ $tipo }}" accept=".pdf" required>
                                    <label for="file-{{ $tipo }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-upload me-1"></i>Seleccionar PDF
                                    </label>
                                    <div class="mt-2">
                                        <small class="text-muted">Tamaño máximo: 5MB</small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-1"></i>Guardar Documento
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

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