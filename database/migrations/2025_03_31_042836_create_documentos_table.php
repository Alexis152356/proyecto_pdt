<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', [
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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos');
    }
};