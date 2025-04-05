<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Archivo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ArchivoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba para subir un documento válido.
     *
     * @return void
     */
    public function test_usuario_puede_subir_documento_valido()
    {
        // Crear un usuario para la prueba
        $user = User::factory()->create();

        // Autenticar al usuario
        $this->actingAs($user);

        // Crear un archivo PDF simulado
        $file = UploadedFile::fake()->create('documento.pdf', 1024, 'application/pdf');

        // Realizar la petición para subir el archivo
        $response = $this->post(route('archivo.store'), [
            'tipo' => 'PERFIL DE PUESTO TECNÓLOGO',
            'documento' => $file,
        ]);

        // Verificar que la respuesta fue exitosa
        $response->assertStatus(302); // Redirección después de subir el archivo

        // Verificar que el archivo fue guardado en la base de datos
        $this->assertDatabaseHas('archivos', [
            'user_id' => $user->id,
            'tipo' => 'PERFIL DE PUESTO TECNÓLOGO',
            'nombre_original' => 'documento.pdf',
        ]);

        // Verificar que el archivo fue almacenado en el sistema de archivos
        Storage::disk('public')->assertExists('archivos/doc_' . $user->id . '_perfil-de-puesto-tecnologo_' . time() . '.pdf');
    }

    /**
     * Prueba para verificar que no se puede subir un archivo no PDF.
     *
     * @return void
     */
    public function test_no_se_puede_subir_archivo_no_pdf()
    {
        // Crear un usuario para la prueba
        $user = User::factory()->create();

        // Autenticar al usuario
        $this->actingAs($user);

        // Crear un archivo que no sea PDF
        $file = UploadedFile::fake()->create('documento.txt', 1024, 'text/plain');

        // Realizar la petición para subir el archivo
        $response = $this->post(route('archivo.store'), [
            'tipo' => 'PERFIL DE PUESTO TECNÓLOGO',
            'documento' => $file,
        ]);

        // Verificar que la respuesta fue un error
        $response->assertSessionHasErrors('documento');
    }

    /**
     * Prueba para verificar que un usuario no puede subir archivo sin tipo.
     *
     * @return void
     */
    public function test_no_se_puede_subir_archivo_sin_tipo()
    {
        // Crear un usuario para la prueba
        $user = User::factory()->create();

        // Autenticar al usuario
        $this->actingAs($user);

        // Crear un archivo PDF simulado
        $file = UploadedFile::fake()->create('documento.pdf', 1024, 'application/pdf');

        // Realizar la petición para subir el archivo sin especificar tipo
        $response = $this->post(route('archivo.store'), [
            'documento' => $file,
        ]);

        // Verificar que la respuesta fue un error
        $response->assertSessionHasErrors('tipo');
    }

    /**
     * Prueba para verificar que un usuario puede eliminar su documento.
     *
     * @return void
     */
    public function test_usuario_puede_eliminar_su_documento()
    {
        // Crear un usuario y un archivo
        $user = User::factory()->create();
        $archivo = Archivo::factory()->create(['user_id' => $user->id]);

        // Autenticar al usuario
        $this->actingAs($user);

        // Realizar la petición para eliminar el archivo
        $response = $this->delete(route('archivo.destroy', $archivo->id));

        // Verificar que la respuesta fue exitosa
        $response->assertStatus(302); // Redirección después de eliminar el archivo

        // Verificar que el archivo fue eliminado de la base de datos
        $this->assertDatabaseMissing('archivos', [
            'id' => $archivo->id,
        ]);

        // Verificar que el archivo fue eliminado del sistema de archivos
        Storage::disk('public')->assertMissing($archivo->ruta);
    }

    /**
     * Prueba para verificar que un usuario no puede eliminar documentos ajenos.
     *
     * @return void
     */
    public function test_usuario_no_puede_eliminar_documentos_ajenos()
    {
        // Crear dos usuarios y un archivo asociado al segundo usuario
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $archivo = Archivo::factory()->create(['user_id' => $user2->id]);

        // Autenticar al primer usuario
        $this->actingAs($user1);

        // Intentar eliminar un archivo ajeno
        $response = $this->delete(route('archivo.destroy', $archivo->id));

        // Verificar que la respuesta es un error 403
        $response->assertStatus(403);
    }
}
