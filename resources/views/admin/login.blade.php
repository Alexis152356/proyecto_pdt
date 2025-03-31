<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador</title>
</head>
<body>
    <h1>Login de Administrador</h1>
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div>
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <button type="submit">Iniciar Sesión</button>
        </div>
    </form>
</body>
</html>