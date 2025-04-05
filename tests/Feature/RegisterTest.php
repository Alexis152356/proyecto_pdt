<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;
use function Pest\Laravel\post;

test('registro exitoso con foto', function () {
    Storage::fake('public');

    $response = post('/register', [
        'nombre' => 'Juan Pérez',
        'edad' => 25,
        'universidad' => 'UNAM',
        'genero' => 'masculino',
        'correo' => 'juan@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'foto' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $response->assertRedirect(route('login'));

    Storage::disk('public')->assertExists(Usuario::first()->foto);

    $this->assertDatabaseHas('usuarios', [
        'correo' => 'juan@example.com',
    ]);
});
