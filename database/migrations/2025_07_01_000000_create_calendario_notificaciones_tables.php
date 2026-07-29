<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_calendario', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('tipo', ['agenda', 'recordatorio', 'visita', 'reunion', 'inspeccion'])->default('agenda');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->foreignId('obra_id')->nullable()->constrained('obras')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // Tabla estándar de notificaciones de Laravel (usada por
        // Illuminate\Notifications\Notifiable en el modelo User).
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('eventos_calendario');
    }
};
