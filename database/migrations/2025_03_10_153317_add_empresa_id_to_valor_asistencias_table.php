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
        Schema::table('valor_asistencias', function (Blueprint $table) {
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('valor_asistencias', function (Blueprint $table) {
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->after('tipo');
        });
    }
};
