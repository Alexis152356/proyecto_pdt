<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0D1B63;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }
        .logout-button {
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .container-content {
            background-color: #D3D3D3;
            padding: 50px;
            text-align: center;
            position: relative;
        }
        .btn-orange {
            background-color: #FF6600;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .image-container {
            position: absolute;
            bottom: 20px;
            right: 50px;
        }
        .footer {
            background-color: #0D1B63;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
    <img src="/img/logo-blue.svg" alt="Logo">
    <form action="{{ route('admin.logout') }}" method="POST">
    @csrf
    <button type="submit" class="logout-button">Cerrar Sesión</button>
</form>

    </div>
    <div class="container-content">
    <h1>Bienvenido</h1>
    <br><br>
        <a href="{{ route('admin.listar.usuarios') }}" class="btn-orange">Gestionar Usuarios</a>
        <br><br><br>
        <a href="{{ route('admin.archivos') }}" class="btn-orange"> Subir Archivos</a>


       

        <div class="image-container">
        <img src="/img/image4.png" alt="Worker" width="150">


           
        </div>
    </div>
    <div class="footer"></div>
</body>
</html>
