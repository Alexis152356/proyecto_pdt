<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/2025_04_01_042142_create_archivos_table.php
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
            'cv',
            'carta_invitacion', 
            'acta_nacimiento',
            'ine',
            'curp',
            'rfc',
            'comprobante_domicilio',
            'certificado_medico'
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