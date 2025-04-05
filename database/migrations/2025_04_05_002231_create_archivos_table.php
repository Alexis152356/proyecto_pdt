<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Primero verificar/crear la tabla users si no existe
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamps();
            });
        }

        // Crear tabla archivos sin relaciones inicialmente
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Temporalmente sin FK
            $table->unsignedBigInteger('revisado_por')->nullable();
            
            $table->enum('tipo', [
                'PERFIL DE PUESTO TECNÓLOGO',
                'GENERALIDADES DEL PROGRAMA DE PDT',
                'LISTA DE DOCUMENTOS UPPER', 
                'CONDUCTAS EN ALMACÉN',
                'FORMATO DE ESTUDIO SOCIOECONOMICO SOLGISTIKA',
                'TRAMITE EN LINEA', 
                'FOTOS',
                'Ficha de datos para dar de alta'
            ]);
            
            $table->string('nombre_original');
            $table->string('ruta');
            $table->string('mime_type');
            $table->unsignedBigInteger('tamano');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentario')->nullable();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();
        });

        // Agregar relaciones después con verificación
        Schema::table('archivos', function (Blueprint $table) {
            // Verificar que exista la columna id en users
            if (Schema::hasColumn('users', 'id')) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                
                $table->foreign('revisado_por')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['revisado_por']);
        });
        
        Schema::dropIfExists('archivos');
        
        // Opcional: eliminar users si lo creamos aquí
        if (Schema::hasTable('users') && !Schema::hasTable('personal_access_tokens')) {
            Schema::dropIfExists('users');
        }
    }
};