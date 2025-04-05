<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: space-between;
            width: 80%;
        }

        .login-box {
            background-color: orange;
            padding: 50px;
            border-radius: 25px;
            width: 400px;
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

        h1 {
            color: #333;
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="logo-container">
        <img src="/img/logo-blue.svg" alt="Logo">
    </div>

    <div class="container">
        <div class="login-box">
            <h1>Login de Administrador</h1>
            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo:</label>
                    <input type="email" name="correo" id="correo" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-dark w-100">Iniciar Sesión</button>
                </div>
            </form>
        </div>
        <div class="image-container">
        <img src="{{ asset('img/image1.png') }}" alt="Ilustración">
        </div>
    </div>

</body>
</html>
