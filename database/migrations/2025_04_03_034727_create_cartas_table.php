<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cartas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            
            // Campos para carta de aceptación
            $table->string('carta_aceptacion')->nullable();
            $table->string('respuesta_aceptacion')->nullable();
            $table->enum('estado_aceptacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentario_aceptacion')->nullable();
            
            // Campos para carta de presentación
            $table->string('carta_presentacion')->nullable();
            $table->string('respuesta_presentacion')->nullable();
            $table->enum('estado_presentacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentario_presentacion')->nullable();
            
            $table->timestamps();
    
            
            // Índice compuesto para evitar duplicados
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartas');
    }
};