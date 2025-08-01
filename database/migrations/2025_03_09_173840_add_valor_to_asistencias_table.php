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
        Schema::table('asistencias', function (Blueprint $table) {
            $table->foreignId('valor_asistencias_id')->constrained('valor_asistencias')->onDelete('cascade')->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->foreignId('valor_asistencias_id')->constrained('valor_asistencias')->onDelete('cascade')->after('tipo');
        });
    }
};
