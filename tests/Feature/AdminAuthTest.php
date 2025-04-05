<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

// 🟢 Test: Registro exitoso de un admin
test('registro exitoso de un administrador', function () {
    $response = $this->post('/admin/register', [
        'nombre' => 'Admin Uno',
        'correo' => 'admin1@example.com',
        'password' => 'adminpassword',
        'password_confirmation' => 'adminpassword',
    ]);

    $response->assertRedirect(route('admin.login'));
    $this->assertDatabaseHas('admins', [
        'correo' => 'admin1@example.com',
    ]);
});

// 🔴 Test: Error al registrar admin con correo duplicado
test('registro de admin falla si el correo ya existe', function () {
    Admin::create([
        'nombre' => 'Admin Existente',
        'correo' => 'duplicado@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post('/admin/register', [
        'nombre' => 'Otro Admin',
        'correo' => 'duplicado@example.com',
        'password' => 'nuevo1234',
        'password_confirmation' => 'nuevo1234',
    ]);

    $response->assertSessionHasErrors('correo');
});
