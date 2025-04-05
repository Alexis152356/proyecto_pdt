<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            
            // Relación con usuarios (asegúrate que la tabla usuarios exista)
            $table->unsignedBigInteger('usuario_id');
            
            // Campo para el revisor (nullable porque puede no estar revisado aún)
            $table->unsignedBigInteger('revisado_por')->nullable();
            
            // Tipo de documento
            $table->enum('tipo', [
                'contrato',
                'cv',
                'carta_invitacion',
                'acta_nacimiento',
                'ine',
                'curp',
                'rfc',
                'nss',
                'comprobante_estudios',
                'comprobante_domicilio',
                'cartas_recomendacion',
                'constancias_cursos',
                'certificado_medico',
                'cuenta_nomina'
            ]);
            
            $table->string('nombre_archivo');
            $table->string('ruta_archivo');
            $table->boolean('es_contrato')->default(false);
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentario')->nullable();
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['usuario_id', 'tipo']);
        });

        // Agregar las relaciones FOREIGN KEY después de crear la tabla
        Schema::table('documentos', function (Blueprint $table) {
            // Relación con la tabla usuarios (ajusta el nombre si es diferente)
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('cascade');
                  
            // Relación con la tabla de usuarios que revisan (puede ser la misma tabla usuarios)
            $table->foreign('revisado_por')
                  ->references('id')
                  ->on('usuarios')  // Asegúrate que esta tabla exista
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Eliminar las claves foráneas primero
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['revisado_por']);
        });
        
        // Luego eliminar la tabla
        Schema::dropIfExists('documentos');
    }
};