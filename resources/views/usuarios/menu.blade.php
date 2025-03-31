<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Usuario</title>
</head>
<body>
    <h1>Bienvenido al Menú de Usuario</h1>
    <p>Has iniciado sesión correctamente.</p>

    <ul>
        <!-- Agregar la ruta "subir.informacion" al botón "Subir información" -->
        <li><a href="{{ route('subir_documentos') }}">Subir información</a></li>
        <li><a href="#">Configuración</a></li>
        <li><a href="#">Notificaciones</a></li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>
</body>
</html>