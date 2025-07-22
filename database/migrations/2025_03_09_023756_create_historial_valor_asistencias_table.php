<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historial_valor_asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('valor_asistencia_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained()->onDelete('set null'); // Quién hizo el cambio
            $table->decimal('valor_anterior', 10, 2);
            $table->decimal('valor_nuevo', 10, 2);
            $table->timestamp('fecha_cambio')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_valor_asistencias');
    }
};
