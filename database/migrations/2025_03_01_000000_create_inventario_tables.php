<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ubicacion')->nullable();
            $table->string('responsable')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock de cada material por almacén (múltiples almacenes).
        Schema::create('almacen_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_id')->constrained('almacenes')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materiales')->cascadeOnDelete();
            $table->decimal('stock', 12, 2)->default(0);
            $table->timestamps();
            $table->unique(['almacen_id', 'material_id']);
        });

        // Kardex: historial de todos los movimientos (entrada, salida, ajuste, transferencia).
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materiales')->cascadeOnDelete();
            $table->foreignId('almacen_id')->constrained('almacenes')->cascadeOnDelete();
            $table->foreignId('almacen_destino_id')->nullable()->constrained('almacenes')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'ajuste', 'transferencia'])->index();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('stock_anterior', 12, 2)->default(0);
            $table->decimal('stock_nuevo', 12, 2)->default(0);
            $table->string('motivo')->nullable()->comment('Compra, obra, ajuste de inventario, devolución, etc.');
            $table->foreignId('obra_id')->nullable()->comment('Se referencia cuando exista el módulo de Obras (Fase 4)');
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('almacen_material');
        Schema::dropIfExists('almacenes');
    }
};
