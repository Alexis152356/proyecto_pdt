<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
        </div>
        <div>
            <label for="edad">Edad:</label>
            <input type="number" name="edad" id="edad" required>
        </div>
        <div>
            <label for="universidad">Universidad:</label>
            <input type="text" name="universidad" id="universidad" required>
        </div>
        <div>
            <label for="genero">Género:</label>
            <select name="genero" id="genero" required>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select>
        </div>
        <div>
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="password_confirmation">Confirmar Contraseña:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <div>
            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="foto">
        </div>
        <div>
            <button type="submit">Registrarse</button>
        </div>
    </form>
</body>
</html>