<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Mano de obra ---
        Schema::create('mano_obras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->comment('Ej: Juan Pérez');
            $table->enum('especialidad', [
                'Albañil', 'Ayudante', 'Maestro', 'Pintor', 'Electricista',
                'Gasfitero', 'Carpintero', 'Soldador', 'Ingeniero', 'Arquitecto',
            ]);
            $table->string('documento', 20)->nullable()->comment('DNI/RUC del trabajador o cuadrilla');
            $table->string('telefono', 20)->nullable();
            $table->enum('tipo_costo', ['hora', 'dia', 'semana', 'mes', 'm2', 'ml', 'm3'])->default('dia');
            $table->decimal('costo', 12, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // --- Equipos y herramientas ---
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->decimal('costo_alquiler_dia', 12, 2)->default(0);
            $table->decimal('costo_mantenimiento', 12, 2)->default(0);
            $table->boolean('disponible')->default(true);
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // --- Maquinaria pesada ---
        Schema::create('maquinarias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->comment('Ej: Retroexcavadora, Grúa, Volquete, Minicargador');
            $table->string('marca')->nullable();
            $table->string('placa', 20)->nullable();
            $table->decimal('costo_hora', 12, 2)->default(0);
            $table->decimal('costo_dia', 12, 2)->default(0);
            $table->decimal('costo_mensual', 12, 2)->default(0);
            $table->boolean('disponible')->default(true);
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // --- Obras / Proyectos ---
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->string('ubicacion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_fin_real')->nullable();
            $table->enum('estado', ['planificacion', 'activa', 'pausada', 'terminada', 'cancelada'])->default('planificacion');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // --- Adjuntos de obra: fotos, planos, documentos, contratos ---
        Schema::create('obra_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obra_id')->constrained('obras')->cascadeOnDelete();
            $table->string('nombre_original');
            $table->string('ruta');
            $table->string('tipo', 20)->comment('foto, plano, documento, contrato');
            $table->string('mime')->nullable();
            $table->foreignId('subido_por')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obra_adjuntos');
        Schema::dropIfExists('obras');
        Schema::dropIfExists('maquinarias');
        Schema::dropIfExists('equipos');
        Schema::dropIfExists('mano_obras');
    }
};
