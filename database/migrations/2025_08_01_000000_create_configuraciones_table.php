<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Almacén genérico de configuraciones del sistema (tema, colores,
 * SMTP, WhatsApp, formatos de PDF/Excel, utilidad por defecto, etc.)
 * como pares clave-valor, para que todo sea editable desde el panel
 * sin necesitar nuevas migraciones por cada ajuste futuro.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->string('grupo', 50)->default('general')->comment('general, smtp, whatsapp, pdf, excel, tema');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
