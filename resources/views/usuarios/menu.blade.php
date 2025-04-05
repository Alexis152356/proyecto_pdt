<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .logo-container {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .logo-container img {
            width: 150px;
        }
        .content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 80%;
            max-width: 1000px;
        }
        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .menu h1 {
            font-size: 28px;
            
        }
        .menu button, .menu a {
            width: 250px;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            text-align: left;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .menu a {
            background-color: navy;
            color: white;
            border: none;
        }
        .menu a:hover {
            background-color: #001f4d;
        }
        .logout-btn {
            background-color: red;
            color: white;
            border: none;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
        .image-container {
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white; /* Color de fondo blanco */
}

.image-container img {
    width: 400px;
    height: auto;
}

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color: orange;
        }
    </style>
</head>
<body>

<div class="logo-container">
        <img src="/img/logo-blue.svg" alt="Logo">
    </div>

    <div class="content">
        <div class="menu">
            <h1><i class="fas fa-user-circle"></i> BIENVENIDO TECNÓLOGO</h1>
            <a href="{{ route('subir_documentos') }}"><i class="fas fa-upload"></i> SUBIR INFORMACIÓN</a>
            <a href="{{ route('ver_archivos') }}"><i class="fas fa-cog"></i> DOCUMENTOS</a>
            <a href="{{ route('cartas.index') }}"><i class="fas fa-envelope"></i> CARTAS</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> CERRAR SESIÓN</button>
            </form>
        </div>

        <div class="image-container">
            <img src="img\imagen.png" alt="Ilustración">
        </div>
    </div>

    <div class="footer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
