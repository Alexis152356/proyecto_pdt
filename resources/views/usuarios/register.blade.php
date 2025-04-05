<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Define la codificación de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Hace la página responsiva -->
    <title>Registro</title> <!-- Título de la página -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Carga de Font Awesome para iconos -->
    <style>
        body {
            display: flex; /* Se utiliza Flexbox para centrar el contenido */
            justify-content: center; /* Centra el contenido horizontalmente */
            align-items: center; /* Centra el contenido verticalmente */
            min-height: 100vh; /* Asegura que el cuerpo de la página ocupe toda la altura de la ventana */
            background-color: #0D1B63; /* Establece el color de fondo */
            margin: 0; /* Elimina los márgenes predeterminados */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Define la fuente */
            padding: 20px; /* Agrega un poco de espacio alrededor */
        }

        .container {
            display: flex; /* Utiliza Flexbox para organizar los elementos dentro de .container */
            width: 100%; /* Ancho completo */
            max-width: 700px; /* Establece un ancho máximo */
            background: white; /* Fondo blanco */
            border-radius: 10px; /* Bordes redondeados */
            overflow: hidden; /* Oculta cualquier contenido que se salga del contenedor */
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2); /* Añade sombra */
            position: relative; /* Necesario para posicionar el logo dentro */
        }

        .left {
            flex: 1; /* Toma el 50% del espacio disponible */
            background: url('/img/image3.png') no-repeat center center; /* Establece una imagen de fondo */
            background-size: cover; /* Asegura que la imagen cubra todo el contenedor */
            min-height: 500px; /* Establece una altura mínima */
        }

        .right {
            flex: 1; /* Toma el 50% del espacio disponible */
            background: #FF6600; /* Fondo naranja */
            padding: 30px; /* Espaciado interno */
            display: flex;
            flex-direction: column; /* Organiza los elementos en columna */
            justify-content: center; /* Centra el contenido verticalmente */
            align-items: center; /* Centra el contenido horizontalmente */
            position: relative; /* Necesario para posicionar el logo */
        }

        .logo-container {
            position: absolute; /* Posiciona el logo en la parte superior izquierda */
            top: 15px; /* Espaciado desde arriba */
            left: 15px; /* Espaciado desde la izquierda */
            background-color: white; /* Fondo blanco */
            padding: 6px; /* Espaciado interno */
            border-radius: 5px; /* Bordes redondeados */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra suave */
        }

        .logo {
            width: 100px; /* Tamaño del logo */
            display: block; /* Bloque para quitar espacios innecesarios */
        }

        h1 {
            color: white; /* Color blanco */
            margin-bottom: 20px; /* Espaciado debajo del título */
            font-weight: 600; /* Peso de la fuente */
            text-align: center; /* Centrado */
            font-size: 1.5rem; /* Tamaño de la fuente */
        }

        form {
            width: 100%; /* El formulario ocupa todo el ancho disponible */
            max-width: 280px; /* Establece un ancho máximo */
        }

        .photo-upload {
            width: 80px; /* Ancho del área para subir la foto */
            height: 80px; /* Alto del área para subir la foto */
            background-color: rgba(0,0,0,0.2); /* Fondo oscuro */
            border-radius: 50%; /* Forma circular */
            margin: 0 auto 15px; /* Centrado horizontalmente y espaciado debajo */
            cursor: pointer; /* Cursor de mano al pasar sobre el área */
            display: flex;
            justify-content: center; /* Centra el icono */
            align-items: center; /* Centra el icono */
            overflow: hidden; /* Oculta las partes que sobresalen */
            border: 2px solid white; /* Borde blanco */
            position: relative; /* Necesario para la posición del icono */
        }

        .photo-upload:hover {
            background-color: rgba(0,0,0,0.3); /* Cambio de fondo al pasar el mouse */
        }

        #photo-icon {
            color: white; /* Color del icono */
            font-size: 1.5rem; /* Tamaño del icono */
            font-weight: bold; /* Peso del icono */
        }

        #preview-image {
            width: 100%; /* La imagen ocupa el 100% del contenedor */
            height: 100%; /* La imagen ocupa el 100% del contenedor */
            object-fit: cover; /* Asegura que la imagen cubra el contenedor sin distorsionarse */
            display: none; /* Oculta la imagen por defecto */
        }

        label {
            color: white; /* Color del texto */
            font-weight: 600; /* Peso del texto */
            display: block; /* Hace que cada label sea un bloque */
            margin-bottom: 4px; /* Espaciado debajo del label */
            margin-top: 12px; /* Espaciado superior */
            font-size: 0.9rem; /* Tamaño de la fuente */
        }

        input, select {
            width: 100%; /* Los campos ocupan el 100% del ancho disponible */
            padding: 10px; /* Espaciado interno */
            margin-bottom: 4px; /* Espaciado inferior */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            background-color: rgba(255,255,255,0.9); /* Fondo blanco con algo de transparencia */
            font-size: 13px; /* Tamaño de la fuente */
        }

        input:focus, select:focus {
            outline: none; /* Elimina el borde al enfocar */
            box-shadow: 0 0 0 2px rgba(0,0,0,0.2); /* Sombra al enfocar */
        }

        button {
            background: black; /* Fondo negro */
            color: white; /* Texto blanco */
            padding: 10px; /* Espaciado interno */
            width: 100%; /* El botón ocupa todo el ancho disponible */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de mano */
            font-size: 15px; /* Tamaño de la fuente */
            font-weight: 600; /* Peso de la fuente */
            margin-top: 20px; /* Espaciado superior */
            transition: all 0.3s ease; /* Transición suave */
        }

        button:hover {
            background: #333; /* Cambia el fondo al pasar el mouse */
            transform: translateY(-2px); /* Sube un poco el botón */
        }

        .form-group {
            margin-bottom: 12px; /* Espaciado inferior en los grupos de formulario */
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column; /* Hace que el contenedor sea una columna en pantallas pequeñas */
                max-width: 400px; /* Ajusta el ancho máximo en pantallas pequeñas */
            }
            
            .left {
                min-height: 200px; /* Reduce la altura mínima en pantallas pequeñas */
            }
            
            .right {
                padding: 25px; /* Reduce el padding en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Contenedor principal -->
        <div class="left"></div> <!-- Lado izquierdo con la imagen de fondo -->
        <div class="right">
            <!-- Lado derecho con el formulario -->
            <div class="logo-container">
                <img src="/img/logo-blue.svg" alt="Logo" class="logo"> <!-- Logo en la esquina superior izquierda -->
            </div>
            
            <h1>Registro</h1> <!-- Título del formulario -->
            
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf <!-- Token CSRF para protección de formularios -->
                
                <!-- Área de subida de foto -->
                <label for="foto" class="photo-upload" id="photo-preview">
                    <span id="photo-icon">+</span> <!-- Icono de agregar imagen -->
                    <img id="preview-image" alt=""> <!-- Imagen previa -->
                    <input type="file" name="foto" id="foto" accept="image/*" style="display: none;"> <!-- Input de archivo, oculto -->
                </label>
                
                <!-- Campos del formulario -->
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" required>
                </div>
                
                <div class="form-group">
                    <label for="edad">Edad:</label>
                    <input type="number" name="edad" id="edad" placeholder="Edad" required>
                </div>
                
                <div class="form-group">
                    <label for="universidad">Universidad:</label>
                    <input type="text" name="universidad" id="universidad" placeholder="Universidad" required>
                </div>
                
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select name="genero" id="genero" required>
                        <option value="">Seleccione...</option> <!-- Opción por defecto -->
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmar contraseña" required>
                </div>
                
                <button type="submit">Registrarse</button> <!-- Botón para enviar el formulario -->
            </form>
        </div>
    </div>

    <script>
        // Función para mostrar una vista previa de la imagen seleccionada
        document.getElementById('foto').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Obtiene el archivo seleccionado
            if (file) {
                const reader = new FileReader(); // Crea un lector de archivos
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result; // Muestra la imagen
                    document.getElementById('preview-image').style.display = 'block'; // Muestra la imagen
                    document.getElementById('photo-icon').style.display = 'none'; // Oculta el icono "+"
                }
                reader.readAsDataURL(file); // Lee el archivo como URL
            }
        });

        // Función para mostrar una vista previa de la imagen seleccionada
    document.getElementById('foto').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Obtiene el archivo seleccionado
        if (file) {
            const reader = new FileReader(); // Crea un lector de archivos
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result; // Muestra la imagen
                document.getElementById('preview-image').style.display = 'block'; // Muestra la imagen
                document.getElementById('photo-icon').style.display = 'none'; // Oculta el icono "+"
            }
            reader.readAsDataURL(file); // Lee el archivo como URL
        }
    });

    // Validación de la contraseña
    document.querySelector('form').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        // Expresión regular para la validación de la contraseña
        const passwordPattern = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
        
        // Verificar que la contraseña cumple con los requisitos
        if (!password.match(passwordPattern)) {
            alert("La contraseña debe tener al menos una letra mayúscula, un carácter especial y un mínimo de 8 caracteres.");
            event.preventDefault(); // Evita que el formulario se envíe
            return;
        }

        // Verificar que las contraseñas coincidan
        if (password !== passwordConfirmation) {
            alert("Las contraseñas no coinciden.");
            event.preventDefault(); // Evita que el formulario se envíe
        }
    });
    </script>
</body>
</html>
