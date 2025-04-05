<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #0D1B63;
            margin: 0;
        }

        .container {
            display: flex;
            width: 800px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .left {
    flex: 1;
    background: url('/img/image3.png') no-repeat center center;
    background-size: cover;
}


        .right {
            flex: 1;
            background: #FF6600;
            padding: 40px 30px; /* Ajusté el padding para darle un poco de espacio alrededor */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1 {
            color: white;
        }

        form {
            width: 100%;
            max-width: 320px; /* Limité el ancho del formulario para que no se vea tan estirado */
            margin-top: 20px; /* Le agregué un pequeño margen en la parte superior */
        }

        label {
            color: white;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0; /* Separación entre los campos */
            border: none;
            border-radius: 5px;
        }

        button {
            background: black;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="left"></div> <!-- Imagen a la izquierda -->
        <div class="right">
            <h1>Registro</h1>
            <form action="{{ route('admin.register') }}" method="POST">
                @csrf
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required>
                
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>
                
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
                
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>
