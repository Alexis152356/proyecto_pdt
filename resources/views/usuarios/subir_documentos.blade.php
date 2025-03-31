<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .document-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .document-actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Subir Documentos Requeridos</h1>
        
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="row">
        @foreach($tiposDocumentos as $tipo => $nombre)
<div class="documento-item">
    <h4>{{ $nombre }}</h4>
    
    @if(isset($documentos[$tipo]))
        <div class="alert alert-success">
            <p>Documento subido: {{ $documentos[$tipo]->nombre_archivo }}</p>
            <div class="btn-group">
                <a href="{{ route('documentos.show', $documentos[$tipo]->id) }}" 
                   target="_blank" 
                   class="btn btn-sm btn-primary">
                   Ver
                </a>
                <form action="{{ route('documentos.destroy', $documentos[$tipo]->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    @else
        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" value="{{ $tipo }}">
            <div class="form-group">
                <input type="file" name="documento" accept=".pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir</button>
        </form>
    @endif
</div>
@endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>