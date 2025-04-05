<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }
        .container {
    display: flex;
    justify-content: space-between;
    width: 80%;
    margin-top: 50px; /* Ajusta este valor según lo necesites */
}

        .login-box {
            background-color: orange;
            padding: 70px;
            border-radius: 25px;
            width: 400px; /* Aumenté el tamaño del cuadro */
            text-align: center;
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            background-color: navy;
            color: white;
            font-size: 16px;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: none;
            background-color: black;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        .logo-container {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
        }
        .logo-container img {
            width: 150px;
        }
        .image-container img {
            width: 550px; /* Aumenté el tamaño de la imagen */
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="/img/logo-blue.svg" alt="Logo">
    </div>
    <div class="container">
        <div class="login-box">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label for="correo">Correo electrónico:</label>
                <input type="email" name="correo" id="correo" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>
                
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
                <br>
                <br>
                <button type="submit">Iniciar</button>
            </form>
        </div>
        <div class="image-container">
            <img src="img/image1.png" alt="Ilustración">
        </div>
    </div>
</body>
</html>
