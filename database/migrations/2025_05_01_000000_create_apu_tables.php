<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Un APU (Análisis de Precios Unitarios) define cuánto cuesta producir
 * UNA unidad de una partida (ej: 1 m2 de muro de ladrillo). Se compone
 * de materiales, mano de obra, equipos y maquinaria, cada uno con su
 * rendimiento y desperdicio. Es reutilizable en cualquier presupuesto.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apus', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('descripcion');
            $table->string('unidad', 20)->comment('m2, m3, ml, und, kg, etc.');
            $table->decimal('rendimiento', 12, 4)->default(1)->comment('Cantidad de unidades que produce una cuadrilla/jornada');
            $table->decimal('porcentaje_herramientas', 5, 2)->default(3)->comment('% sobre mano de obra, práctica estándar del sector');
            $table->decimal('porcentaje_costos_indirectos', 5, 2)->default(10);
            $table->decimal('porcentaje_utilidad', 5, 2)->default(10);
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('apu_materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apu_id')->constrained('apus')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materiales')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4)->comment('Cantidad de material por unidad de APU');
            $table->decimal('desperdicio_pct', 5, 2)->default(5);
            $table->decimal('precio_unitario', 12, 4)->comment('Snapshot del precio al momento de crear el APU');
            $table->timestamps();
        });

        Schema::create('apu_mano_obra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apu_id')->constrained('apus')->cascadeOnDelete();
            $table->foreignId('mano_obra_id')->constrained('mano_obras')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4)->comment('Cantidad de jornales/horas por unidad de APU');
            $table->decimal('costo_unitario', 12, 4);
            $table->timestamps();
        });

        Schema::create('apu_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apu_id')->constrained('apus')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4);
            $table->decimal('costo_unitario', 12, 4);
            $table->timestamps();
        });

        Schema::create('apu_maquinarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apu_id')->constrained('apus')->cascadeOnDelete();
            $table->foreignId('maquinaria_id')->constrained('maquinarias')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4);
            $table->decimal('costo_unitario', 12, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apu_maquinarias');
        Schema::dropIfExists('apu_equipos');
        Schema::dropIfExists('apu_mano_obra');
        Schema::dropIfExists('apu_materiales');
        Schema::dropIfExists('apus');
    }
};
