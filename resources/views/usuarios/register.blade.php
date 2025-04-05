<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #0D1B63;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 700px; /* Reduced from 800px */
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .left {
            flex: 1;
            background: url('/img/image3.png') no-repeat center center;
            background-size: cover;
            min-height: 500px; /* Added fixed height */
        }

        .right {
            flex: 1;
            background: #FF6600;
            padding: 30px; /* Reduced from 40px */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .logo-container {
            position: absolute;
            top: 15px; /* Reduced from 20px */
            left: 15px; /* Reduced from 20px */
            background-color: white;
            padding: 6px; /* Reduced from 8px */
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo {
            width: 100px; /* Reduced from 120px */
            display: block;
        }

        h1 {
            color: white;
            margin-bottom: 20px; /* Reduced from 30px */
            font-weight: 600;
            text-align: center;
            font-size: 1.5rem; /* Added fixed font size */
        }

        form {
            width: 100%;
            max-width: 280px; /* Reduced from 320px */
        }

        .photo-upload {
            width: 80px; /* Reduced from 100px */
            height: 80px; /* Reduced from 100px */
            background-color: rgba(0,0,0,0.2);
            border-radius: 50%;
            margin: 0 auto 15px; /* Reduced from 20px */
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 2px solid white; /* Reduced from 3px */
            position: relative;
        }

        .photo-upload:hover {
            background-color: rgba(0,0,0,0.3);
        }

        #photo-icon {
            color: white;
            font-size: 1.5rem; /* Reduced from 2rem */
            font-weight: bold;
        }

        #preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        label {
            color: white;
            font-weight: 600;
            display: block;
            margin-bottom: 4px; /* Reduced from 5px */
            margin-top: 12px; /* Reduced from 15px */
            font-size: 0.9rem; /* Added smaller font size */
        }

        input, select {
            width: 100%;
            padding: 10px; /* Reduced from 12px */
            margin-bottom: 4px; /* Reduced from 5px */
            border: none;
            border-radius: 5px;
            background-color: rgba(255,255,255,0.9);
            font-size: 13px; /* Reduced from 14px */
        }

        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0,0,0,0.2);
        }

        button {
            background: black;
            color: white;
            padding: 10px; /* Reduced from 12px */
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px; /* Reduced from 16px */
            font-weight: 600;
            margin-top: 20px; /* Reduced from 25px */
            transition: all 0.3s ease;
        }

        button:hover {
            background: #333;
            transform: translateY(-2px);
        }

        .form-group {
            margin-bottom: 12px; /* Reduced from 15px */
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 400px;
            }
            
            .left {
                min-height: 200px;
            }
            
            .right {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left"></div>
        <div class="right">
            <div class="logo-container">
                <img src="/img/logo-blue.svg" alt="Logo" class="logo">
            </div>
            
            <h1>Registro</h1>
            
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <label for="foto" class="photo-upload" id="photo-preview">
                    <span id="photo-icon">+</span>
                    <img id="preview-image" alt="">
                    <input type="file" name="foto" id="foto" accept="image/*" style="display: none;">
                </label>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
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
                        <option value="">Seleccione...</option>
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
                
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('foto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-image').style.display = 'block';
                    document.getElementById('photo-icon').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>