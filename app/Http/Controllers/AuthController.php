<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('usuarios.register');
    }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer',
            'universidad' => 'required|string|max:255',
            'genero' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar la foto
        ]);
    
        // Guardar la foto si se proporciona
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('usuarios', 'public'); // Guardar la foto en storage/app/public/usuarios
        }
    
        // Crear el usuario
        Usuario::create([
            'nombre' => $request->nombre,
            'edad' => $request->edad,
            'universidad' => $request->universidad,
            'genero' => $request->genero,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'foto' => $fotoPath,
        ]);
    
        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }
    public function showLoginForm()
    {
        return view('usuarios.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'correo' => 'required|email',
        'password' => 'required',
    ]);

    $usuario = Usuario::where('correo', $request->correo)->first();

    if (!$usuario) {
        return back()->withErrors(['correo' => 'Correo no registrado'])->withInput();
    }

    // Debugging detallado
    \Log::debug('Intento de login', [
        'input_pass' => $request->password,
        'db_pass' => $usuario->password,
        'hash_check' => Hash::check($request->password, $usuario->password),
        'hash_of_input' => Hash::make($request->password),
        'auth_check' => auth()->attempt([
            'correo' => $request->correo,
            'password' => $request->password
        ])
    ]);

    if (!Hash::check($request->password, $usuario->password)) {
        return back()->withErrors(['password' => 'Contraseña incorrecta'])->withInput();
    }

    auth()->login($usuario); // Asegúrate de usar el guard correcto
    return redirect()->route('menu');
}
    public function logout(Request $request)
{
    // Aquí puedes agregar lógica adicional si es necesario
    auth()->logout(); // Cierra la sesión del usuario
    return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
}

public function mostrarInformacionUsuario($id)
{
    $usuario = Usuario::with('documentos')->findOrFail($id);
    return view('informacion_usuario', compact('usuario'));
}
}
