<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Primero verificar/crear la tabla usuarios si no existe
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('correo')->unique();
                $table->string('password');
                $table->integer('edad')->nullable();
                $table->string('universidad')->nullable();
                $table->string('genero')->nullable();
                $table->string('foto')->nullable();
                $table->rememberToken();
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamps();
            });
        }

        // Crear tabla archivos
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
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

            // Índices para mejorar el rendimiento
            $table->index('user_id');
            $table->index('tipo');
            $table->index('estado');
        });

        // Agregar relaciones después de crear ambas tablas
        Schema::table('archivos', function (Blueprint $table) {
            // Verificar que existan las tablas y columnas
            if (Schema::hasTable('usuarios') && Schema::hasColumn('usuarios', 'id')) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                
                $table->foreign('revisado_por')
                    ->references('id')
                    ->on('usuarios')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }
        });
    }

    public function down()
    {
        // Eliminar las claves foráneas primero
        Schema::table('archivos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['revisado_por']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['tipo']);
            $table->dropIndex(['estado']);
        });
        
        // Eliminar las tablas
        Schema::dropIfExists('archivos');
        
        // Opcional: eliminar usuarios solo si fue creada aquí
        if (Schema::hasTable('usuarios') && !Schema::hasTable('personal_access_tokens')) {
            Schema::dropIfExists('usuarios');
        }
    }
};