<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla "empresa": aunque técnicamente permite varias filas,
 * el sistema opera como singleton (una sola empresa configurada),
 * gestionada desde Configuración > Empresa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('nombre');
            $table->string('ruc', 20)->unique();
            $table->string('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('pagina_web')->nullable();
            $table->json('redes_sociales')->nullable()->comment('{facebook, instagram, tiktok, ...}');
            $table->decimal('igv', 5, 2)->default(18.00)->comment('Porcentaje de IGV/IVA por defecto');
            $table->string('moneda', 10)->default('PEN')->comment('Código de moneda ISO, ej: PEN, USD');
            $table->string('firma')->nullable()->comment('Ruta de imagen de firma digital para PDFs');
            $table->text('pie_pagina_pdf')->nullable()->comment('Texto de pie de página en documentos generados');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
