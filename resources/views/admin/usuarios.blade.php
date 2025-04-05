<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tarjetas de usuario */
        .user-card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .user-img {
            height: 150px;
            object-fit: cover;
        }
        .user-docs-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Botones azul fuerte */
        .btn-primary {
            background-color: #0033A0; /* Azul fuerte */
            border-color: #0033A0;
        }
        .btn-primary:hover {
            background-color: #002080; /* Azul más oscuro */
            border-color: #002080;
        }

        /* Borde inferior anaranjado más grande */
        .bottom-border {
            width: 100%;
            height: 100px; /* Aumentado el tamaño del borde */
            background-color: #FF8000; /* Anaranjado */
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        /* Imagen en la parte inferior */
        .bottom-image {
            position: absolute;
            bottom: 10px;
            left: 90%;
            transform: translateX(-50%);
            max-width: 250px; /* Ajustar tamaño según necesidad */
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-users me-2"></i>Usuarios Registrados</h1>
            <a href="{{ route('admin.menu') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Menú
            </a>
        </div>

        <div class="row">
            @foreach($usuarios as $usuario)
            <div class="col-md-4 mb-4">
                <div class="card user-card h-100">
                    @if($usuario->foto)
                    <img src="{{ Storage::url($usuario->foto) }}" class="card-img-top user-img" alt="Foto de {{ $usuario->nombre }}">
                    @else
                    <div class="text-center py-4 bg-light">
                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                    </div>
                    @endif
                    
                    @if($usuario->documentos->count() > 0)
                    <span class="badge bg-primary user-docs-badge">
                        {{ $usuario->documentos->count() }} doc(s)
                    </span>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $usuario->nombre }}</h5>
                        <p class="card-text text-muted">
                            <i class="fas fa-envelope me-2"></i>{{ $usuario->correo }}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Registrado el {{ $usuario->created_at->format('d/m/Y') }}
                            </small>
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('admin.ver.usuario', $usuario->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-folder-open me-2"></i>Ver Documentos
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Borde inferior anaranjado más grande -->
    <div class="bottom-border"></div>

    <!-- Imagen en la parte inferior -->
    <img src="\img\image6.png" alt="Imagen de logística" class="bottom-image">

    <!-- Iconos y JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
