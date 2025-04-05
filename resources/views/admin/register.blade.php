<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Define el conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Asegura que el sitio se vea bien en dispositivos móviles -->
    <title>Registro de Administrador</title> <!-- Título de la página -->
    <style>
        /* Estilos generales del cuerpo de la página */
        body {
            display: flex; /* Usamos flexbox para centrar el contenido */
            justify-content: center; /* Centra el contenido horizontalmente */
            align-items: center; /* Centra el contenido verticalmente */
            height: 100vh; /* Asegura que el cuerpo ocupe toda la altura de la pantalla */
            background-color: #0D1B63; /* Color de fondo oscuro */
            margin: 0; /* Elimina márgenes predeterminados */
        }

        /* Estilos para el contenedor principal que contiene las dos secciones */
        .container {
            display: flex; /* Flexbox para una distribución de dos columnas */
            width: 800px; /* Define el ancho del contenedor */
            background: white; /* Fondo blanco para el contenedor */
            border-radius: 10px; /* Bordes redondeados */
            overflow: hidden; /* Evita que el contenido se desborde */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1); /* Sombra sutil alrededor del contenedor */
        }

        /* Estilos para la sección izquierda (imagen de fondo) */
        .left {
            flex: 1; /* La sección izquierda ocupa la mitad del espacio disponible */
            background: url('/img/image3.png') no-repeat center center; /* Establece una imagen de fondo */
            background-size: cover; /* Asegura que la imagen cubra toda el área disponible */
        }

        /* Estilos para la sección derecha (formulario) */
        .right {
            flex: 1; /* La sección derecha ocupa la otra mitad */
            background: #FF6600; /* Fondo naranja */
            padding: 40px 30px; /* Relleno alrededor del formulario */
            display: flex;
            flex-direction: column; /* Organiza los elementos de arriba hacia abajo */
            justify-content: center; /* Centra el contenido verticalmente */
            align-items: center; /* Centra el contenido horizontalmente */
        }

        /* Estilos para el título */
        h1 {
            color: white; /* Título en blanco */
        }

        /* Estilos para el formulario */
        form {
            width: 100%; /* El formulario ocupa todo el ancho disponible */
            max-width: 320px; /* Limita el ancho máximo del formulario */
            margin-top: 20px; /* Un pequeño margen superior */
        }

        /* Estilos para las etiquetas de los campos del formulario */
        label {
            color: white; /* Etiquetas en blanco */
            font-weight: bold; /* Hacer las etiquetas en negrita */
        }

        /* Estilos para los campos de entrada del formulario */
        input {
            width: 100%; /* Los campos de entrada ocupan todo el ancho disponible */
            padding: 10px; /* Relleno dentro de los campos */
            margin: 8px 0; /* Separación entre los campos */
            border: none; /* Sin bordes */
            border-radius: 5px; /* Bordes redondeados */
        }

        /* Estilos para el botón de envío */
        button {
            background: black; /* Fondo negro */
            color: white; /* Texto blanco */
            padding: 12px; /* Relleno alrededor del texto */
            width: 100%; /* El botón ocupa todo el ancho disponible */
            border: none; /* Sin bordes */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
            font-size: 16px; /* Tamaño de la fuente */
            margin-top: 15px; /* Margen superior */
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Sección izquierda con la imagen de fondo -->
        <div class="left"></div>
        
        <!-- Sección derecha con el formulario -->
        <div class="right">
            <h1>Registro</h1>
            <!-- Formulario de registro -->
            <form action="{{ route('admin.register') }}" method="POST">
                @csrf <!-- Token de seguridad de Laravel para evitar ataques CSRF -->
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required> <!-- Campo para nombre -->
                
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" required> <!-- Campo para correo electrónico -->
                
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required> <!-- Campo para contraseña -->
                
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required> <!-- Campo para confirmar contraseña -->
                
                <button type="submit">Registrarse</button> <!-- Botón para enviar el formulario -->
            </form>
        </div>
    </div>
</body>
</html>
