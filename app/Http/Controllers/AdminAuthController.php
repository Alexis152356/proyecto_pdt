<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Mostrar el formulario de registro de administradores
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    // Procesar el registro de administradores
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }

    // Mostrar el formulario de login de administradores
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Procesar el login de administradores
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('correo', $request->correo)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Iniciar sesión manualmente
            auth()->guard('admin')->login($admin);

            // Autenticación exitosa
            return redirect()->route('admin.menu');
        }

        // Si la autenticación falla, regresa con un mensaje de error
        return back()->withErrors(['correo' => 'Credenciales incorrectas.'])->withInput();
    }

    // Cerrar sesión de administradores
    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Has cerrado sesión correctamente.');
    }
}