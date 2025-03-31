<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-card {
            transition: all 0.3s ease;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1>Bienvenido al Menú de Administrador</h1>
        <p>Has iniciado sesión correctamente.</p>

        <div class="list-group">
            <!-- Botón modificado para listar usuarios -->
            <a href="{{ route('admin.listar.usuarios') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-users me-2"></i> Gestionar Usuarios
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <i class="fas fa-cog me-2"></i> Configuración
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <i class="fas fa-chart-bar me-2"></i> Reportes
            </a>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </button>
        </form>
    </div>

    <!-- Iconos de Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>