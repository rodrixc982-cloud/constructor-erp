<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->string('imagen')->nullable();
            $table->string('codigo', 50)->unique()->comment('Código interno, autogenerado si no se especifica');
            $table->string('codigo_barras', 50)->nullable()->unique();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->string('unidad', 20)->default('UND')->comment('UND, KG, M2, M3, BLS, GAL, etc.');
            $table->decimal('precio_compra', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);
            $table->decimal('stock', 12, 2)->default(0);
            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->decimal('iva', 5, 2)->default(18.00);
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
