<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Compras: orden de compra a proveedores ---
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
            $table->foreignId('presupuesto_id')->nullable()->constrained('presupuestos')->nullOnDelete()
                ->comment('Si la orden se generó automáticamente desde un presupuesto');
            $table->date('fecha');
            $table->enum('estado', ['pendiente', 'aprobada', 'recibida', 'cancelada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orden_compra_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->constrained('ordenes_compra')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materiales')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->timestamps();
        });

        // --- Caja: ingresos y egresos ---
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['ingreso', 'egreso'])->index();
            $table->string('concepto');
            $table->decimal('monto', 12, 2);
            $table->date('fecha');
            $table->foreignId('presupuesto_id')->nullable()->constrained('presupuestos')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // --- Facturación: cotización, proforma, factura, boleta, orden de servicio ---
        Schema::create('documentos_venta', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['cotizacion', 'proforma', 'factura', 'boleta', 'orden_servicio']);
            $table->string('serie', 10)->nullable();
            $table->string('numero', 20);
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('presupuesto_id')->nullable()->constrained('presupuestos')->nullOnDelete();
            $table->date('fecha');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('igv', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('estado', ['emitido', 'pagado', 'anulado'])->default('emitido');
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tipo', 'serie', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_venta');
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('orden_compra_items');
        Schema::dropIfExists('ordenes_compra');
    }
};
