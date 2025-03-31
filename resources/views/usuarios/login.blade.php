<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Login</h1>
    
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" value="{{ old('correo') }}" required autofocus>
            @error('correo')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <button type="submit">Iniciar Sesión</button>
        </div>
    </form>
</body>
</html>