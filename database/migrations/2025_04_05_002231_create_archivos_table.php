<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        // Crear tabla tipo_documentos primero
        if (!Schema::hasTable('tipo_documentos')) {
            Schema::create('tipo_documentos', function (Blueprint $table) {
                $table->id();
                $table->string('clave')->unique();
                $table->string('nombre');
                $table->boolean('custom')->default(false);
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });

            // Insertar tipos de documentos iniciales
            $this->insertInitialDocumentTypes();
        }

        // Crear tabla archivos
        if (!Schema::hasTable('archivos')) {
            Schema::create('archivos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('revisado_por')->nullable();
                
                // Cambiado de ENUM a string para soportar tipos dinámicos
                $table->string('tipo');
                
                $table->string('nombre_original');
                $table->string('ruta');
                $table->string('mime_type');
                $table->unsignedBigInteger('tamano');
                $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
                $table->text('comentario')->nullable();
                $table->timestamp('revisado_at')->nullable();
                $table->boolean('es_custom')->default(false);
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

                if (Schema::hasTable('tipo_documentos') && Schema::hasColumn('tipo_documentos', 'clave')) {
                    $table->foreign('tipo')
                        ->references('clave')
                        ->on('tipo_documentos')
                        ->onUpdate('cascade');
                }
            });
        }
    }

    protected function insertInitialDocumentTypes()
    {
        $tipos = [
            ['PERFIL_DE_PUESTO_TECNOLOGO', 'Perfil de Puesto Tecnólogo', false],
            ['GENERALIDADES_DEL_PROGRAMA_DE_PDT', 'Generalidades del Programa de PDT', false],
            ['LISTA_DE_DOCUMENTOS_UPPER', 'Lista de Documentos UPPER', false],
            ['CONDUCTAS_EN_ALMACEN', 'Conductas en Almacén', false],
            ['FORMATO_DE_ESTUDIO_SOCIOECONOMICO_SOLGISTIKA', 'Formato de Estudio Socioeconómico Solgistika', false],
            ['TRAMITE_EN_LINEA', 'Trámite en Línea', false],
            ['FOTOS', 'Fotos', false],
            ['FICHA_DE_DATOS_PARA_DAR_DE_ALTA', 'Ficha de Datos para Alta', false]
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documentos')->insert([
                'clave' => $tipo[0],
                'nombre' => $tipo[1],
                'custom' => $tipo[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down()
    {
        // Eliminar las claves foráneas primero
        Schema::table('archivos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['revisado_por']);
            $table->dropForeign(['tipo']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['tipo']);
            $table->dropIndex(['estado']);
        });
        
        // Eliminar las tablas en orden correcto
        Schema::dropIfExists('archivos');
        Schema::dropIfExists('tipo_documentos');
        
        // Opcional: eliminar usuarios solo si fue creada aquí
        if (Schema::hasTable('usuarios') && !Schema::hasTable('personal_access_tokens')) {
            Schema::dropIfExists('usuarios');
        }
    }
};