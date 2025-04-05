<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            
            // Cambia esto según tu estructura real:
            // Opción 1: Si usas users normales
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Opción 2: Si usas admins como en tu modelo
            // $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            
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
            $table->foreignId('revisado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Asegúrate que las columnas referenciadas existan
            $table->index(['user_id', 'tipo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('archivos');
    }
};