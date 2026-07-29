<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('obra_id')->nullable()->constrained('obras')->nullOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha');
            $table->unsignedSmallInteger('validez_dias')->default(30);
            $table->string('moneda', 10)->default('PEN');
            $table->decimal('igv', 5, 2)->default(18);
            $table->decimal('utilidad_pct', 5, 2)->default(10)->comment('Utilidad general del presupuesto, se puede sobreescribir por partida vía el APU');
            $table->decimal('descuento_pct', 5, 2)->default(0);
            $table->enum('estado', ['borrador', 'aprobado', 'rechazado', 'archivado'])->default('borrador');
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('presupuesto_padre_id')->nullable()->constrained('presupuestos')->nullOnDelete()
                ->comment('Referencia al presupuesto original cuando este registro es una versión o duplicado');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Partidas basadas en un APU (o completamente manuales, apu_id nulo).
        Schema::create('presupuesto_partidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('apu_id')->nullable()->constrained('apus')->nullOnDelete();
            $table->unsignedInteger('orden')->default(0);
            $table->string('descripcion');
            $table->string('unidad', 20);
            $table->decimal('metrado', 12, 4)->default(0)->comment('Cantidad/metrado de la partida en el presupuesto');
            $table->decimal('precio_unitario', 12, 4)->default(0)->comment('Snapshot editable del precio unitario del APU');
            $table->timestamps();
        });

        // Otros gastos directos del presupuesto que no vienen de un APU:
        // transporte, hospedaje, viáticos, seguros, herramientas sueltas, otros.
        Schema::create('presupuesto_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->enum('tipo', ['transporte', 'hospedaje', 'viaticos', 'seguro', 'herramienta', 'otro'])->default('otro');
            $table->string('concepto');
            $table->decimal('cantidad', 12, 4)->default(1);
            $table->decimal('precio_unitario', 12, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_gastos');
        Schema::dropIfExists('presupuesto_partidas');
        Schema::dropIfExists('presupuestos');
    }
};
