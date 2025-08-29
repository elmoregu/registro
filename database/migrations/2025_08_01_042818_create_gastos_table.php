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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade'); // Relación con empresas
            $table->string('descripcion'); // Descripción del gasto
            $table->decimal('monto', 10, 2); // Monto del gasto
            $table->date('fecha'); // Fecha del gasto
            $table->string('tipo'); // Tipo de gasto (por ejemplo, 'operativo', 'mantenimiento', etc.)
            $table->string('obs')->nullable(); // Observaciones opcionales
            $table->string('estado')->default('pendiente'); // Estado del gasto (pendiente, aprobado, rechazado)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
