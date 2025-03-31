<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentoController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

Route::get('create', function () {
    return view('usuarios.create');
});

// Autenticación de usuarios normales
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas de usuarios normales
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/menu', function () {
        return view('usuarios.menu');
    })->name('menu');

    // Rutas de documentos para usuarios normales
    Route::prefix('documentos')->group(function () {
        Route::get('/subir', [DocumentoController::class, 'index'])->name('subir_documentos');
        Route::post('/', [DocumentoController::class, 'store'])->name('documentos.store');
        Route::get('/{id}', [DocumentoController::class, 'show'])->name('documentos.show');
        Route::delete('/{id}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
    });
});

// Rutas de administradores
Route::prefix('admin')->group(function () {
    // Autenticación de admin (pública)
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Rutas protegidas de admin
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/menu', function () {
            return view('admin.menu');
        })->name('admin.menu');

        Route::get('/gestionar-usuarios', [AdminController::class, 'gestionarUsuarios'])
            ->name('admin.gestionar.usuarios');
            
        Route::get('/usuario/{id}', [AdminController::class, 'verUsuario'])
            ->name('admin.ver.usuario');
            
        // Ruta para ver documentos como admin
        Route::get('/documentos/{id}', [DocumentoController::class, 'show'])
            ->name('admin.documentos.show');
    });
});



Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // ... otras rutas
    
    // Nueva ruta para listar usuarios
    Route::get('/usuarios', [AdminController::class, 'listarUsuarios'])
         ->name('admin.listar.usuarios');
         
    // Ruta para ver documentos de un usuario específico
    Route::get('/usuarios/{id}', [AdminController::class, 'verUsuario'])
         ->name('admin.ver.usuario');
});

// En routes/web.php
Route::get('/temp-pdf/{file}', function ($file) {
    $path = storage_path('app/documentos/'.$file);
    return response()->file($path, [
        'Content-Type' => 'application/pdf'
    ]);
})->where('file', '.*');




Route::get('/admin/usuarios', [AdminController::class, 'listarUsuarios'])
     ->name('admin.listar.usuarios');


     Route::get('/admin/usuarios', [DocumentoController::class, 'listarUsuarios'])
     ->name('admin.listar.usuarios');




     Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
        Route::get('/menu', function () {
            return view('admin.menu');
        })->name('admin.menu');
    
        Route::get('/gestionar-usuarios', [AdminController::class, 'gestionarUsuarios'])
            ->name('admin.gestionar.usuarios');
            
        Route::get('/usuarios', [AdminController::class, 'listarUsuarios'])
            ->name('admin.listar.usuarios');
            
        Route::get('/usuario/{id}', [AdminController::class, 'verUsuario'])
            ->name('admin.ver.usuario');
            
        Route::get('/documentos/{id}', [DocumentoController::class, 'show'])
            ->name('admin.documentos.show');
    });