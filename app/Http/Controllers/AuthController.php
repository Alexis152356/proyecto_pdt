<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'edad' => 'required|integer|min:18', // Aseguramos que la edad sea mayor o igual a 18
            'universidad' => 'required|string|max:255',
            'genero' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios',
            'password' => [
                'required',
                'string',
                'min:8', // Mínimo de 8 caracteres
                'confirmed', // Confirmación de la contraseña
                'regex:/[A-Z]/', // Al menos una mayúscula
                'regex:/[a-z]/', // Al menos una minúscula
                'regex:/[0-9]/', // Al menos un número
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // Al menos un carácter especial
                // No permitir solo números
                function ($attribute, $value, $fail) {
                    if (is_numeric($value)) {
                        $fail('La contraseña no puede ser solo números.');
                    }
                },
            ],
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

        // Validamos la contraseña con Hash::check() y no generamos un nuevo hash
        if (!Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta'])->withInput();
        }

        // Si la contraseña es correcta, se autentica el usuario
        Auth::login($usuario); // Usamos el método Auth para iniciar sesión

        return redirect()->route('menu');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }

    public function mostrarInformacionUsuario($id)
    {
        $usuario = Usuario::with('documentos')->findOrFail($id);
        return view('informacion_usuario', compact('usuario'));
    }
}
