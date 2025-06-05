<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CartaUsuarioController;
use App\Http\Controllers\CartaAdminController;
// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
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


    Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
        // ... otras rutas
        
        // Rutas para aprobar/rechazar documentos
        Route::post('/documentos/{id}/aprobar', [DocumentoController::class, 'aprobarDocumento'])
             ->name('documentos.aprobar');
             
        Route::post('/documentos/{id}/rechazar', [DocumentoController::class, 'rechazarDocumento'])
             ->name('documentos.rechazar');
    });



    Route::post('/documentos/{id}/aprobar', [DocumentoController::class, 'aprobarDocumento'])
     ->name('documentos.aprobar')
     ->middleware('auth:admin');

Route::post('/documentos/{id}/rechazar', [DocumentoController::class, 'rechazarDocumento'])
     ->name('documentos.rechazar')
     ->middleware('auth:admin');



// Para usuarios normales
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('usuario.dashboard'); // <- Nota el nombre aquí
});

// O si usas un controlador
Route::get('/dashboard', [UserController::class, 'dashboard'])
     ->name('usuario.dashboard')
     ->middleware('auth');




     Route::middleware(['auth'])->group(function () {
        Route::get('/mis-documentos', [ArchivoController::class, 'verArchivos'])->name('ver_archivos');
    });




    Route::get('/archivos/{archivo}', [ArchivoController::class, 'show'])->name('archivos.show');
Route::delete('/archivos/{archivo}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');

Route::middleware(['auth'])->group(function () {
    Route::delete('/archivos/{archivo}', [ArchivoController::class, 'destroy'])
         ->name('archivos.destroy');
});



















     Route::middleware(['auth'])->group(function () {
        // Rutas para usuarios normales
        Route::get('/mis-archivos', [ArchivoController::class, 'verArchivos'])->name('archivos.ver');
        
        // Rutas para administradores
        Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
            Route::get('/archivos', [ArchivoController::class, 'index'])->name('admin.archivos');
            Route::post('/archivos', [ArchivoController::class, 'store'])->name('archivos.store');
            Route::get('/archivos/{id}', [ArchivoController::class, 'show'])->name('archivos.show');
            Route::delete('/archivos/{id}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');
            Route::post('/archivos/{id}/aprobar', [ArchivoController::class, 'aprobar'])->name('archivos.aprobar');
            Route::post('/archivos/{id}/rechazar', [ArchivoController::class, 'rechazar'])->name('archivos.rechazar');
        });
    });



    Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // O tu controlador correspondiente
        })->name('dashboard');
        
        // ... otras rutas de admin
    });





    Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
        // Cambia todas las rutas de 'documentos' a 'archivos'
        Route::get('/archivos', [ArchivoController::class, 'index'])->name('admin.archivos');
        Route::post('/archivos', [ArchivoController::class, 'store'])->name('archivos.store');
        Route::get('/archivos/{id}', [ArchivoController::class, 'show'])->name('archivos.show');
        Route::delete('/archivos/{id}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');
    });

    






// Para usuarios normales
Route::middleware(['auth'])->prefix('cartas')->group(function () {
    Route::get('/', [CartaUsuarioController::class, 'index'])->name('cartas.index');
    Route::post('/', [CartaUsuarioController::class, 'store'])->name('cartas.store');
});

// Para admin (versión limpia y consistente)
Route::prefix('admin')->middleware(['auth:admin'])->name('admin.')->group(function () {
    Route::prefix('cartas')->group(function () {
        Route::get('/', [CartaAdminController::class, 'index'])->name('cartas.index');
        Route::post('/responder/{id}', [CartaAdminController::class, 'responder'])->name('cartas.responder');
    });
});

Route::post('/cartas/{id}/subir-respuesta/{tipo}', [CartaAdminController::class, 'subirRespuesta'])
     ->name('admin.cartas.subir-respuesta');

Route::delete('/cartas/{id}/eliminar-respuesta/{tipo}', [CartaAdminController::class, 'eliminarRespuesta'])
     ->name('admin.cartas.eliminar-respuesta');






    


     Route::post('/admin/cartas/{id}/responder', [CartaAdminController::class, 'responder'])
    ->name('admin.cartas.responder')
    ->middleware('auth'); // Asegúrate de proteger esta ruta




    // Rutas para el administrador
Route::prefix('admin')->group(function() {
    Route::get('/cartas', [CartaAdminController::class, 'index'])->name('admin.cartas.index');
    Route::post('/cartas/{id}/subir-respuesta/{tipo}', [CartaAdminController::class, 'subirRespuesta'])->name('admin.cartas.subir-respuesta');
    Route::delete('/cartas/{id}/eliminar-respuesta/{tipo}', [CartaAdminController::class, 'eliminarRespuesta'])->name('admin.cartas.eliminar-respuesta');
    Route::post('/cartas/{id}/responder', [CartaAdminController::class, 'responder'])->name('admin.cartas.responder');
});



















// routes/web.php o routes/admin.php


// Ruta para revisar cartas con filtro opcional por usuario
Route::get('/admin/cartas/revisar/{usuario_id?}', [CartaAdminController::class, 'index'])
     ->name('admin.revisar.cartas')
     ->middleware('auth'); // Añade middlewares si es necesario

// Rutas para las acciones de cartas (ya las tienes)
Route::post('/admin/cartas/subir-respuesta/{id}/{tipo}', [CartaAdminController::class, 'subirRespuesta'])
     ->name('admin.cartas.subir-respuesta');

Route::delete('/admin/cartas/eliminar-respuesta/{id}/{tipo}', [CartaAdminController::class, 'eliminarRespuesta'])
     ->name('admin.cartas.eliminar-respuesta');

Route::post('/admin/cartas/responder/{id}', [CartaAdminController::class, 'responder'])
     ->name('admin.cartas.responder');


     Route::get('/admin/limpiar-duplicados', [CartaAdminController::class, 'limpiarDuplicados']);





     Route::post('/register', [AuthController::class, 'register'])->name('register');




     // routes/web.php




Route::middleware(['auth'])->group(function () {
    // Rutas para archivos
    Route::resource('archivos', ArchivoController::class)->except(['create', 'edit']);
    
    // Ruta para agregar nuevos tipos de documentos
    Route::post('/admin/tipos-documentos', [ArchivoController::class, 'addDocumentType'])
         ->name('admin.add-document-type');
         
    // Rutas adicionales
    Route::get('/archivos/{id}/aprobar', [ArchivoController::class, 'aprobar'])->name('archivos.aprobar');
    Route::post('/archivos/{id}/rechazar', [ArchivoController::class, 'rechazar'])->name('archivos.rechazar');
    Route::get('/mis-archivos', [ArchivoController::class, 'verArchivos'])->name('archivos.ver');
});
