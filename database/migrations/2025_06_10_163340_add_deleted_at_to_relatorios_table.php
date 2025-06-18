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
        Schema::table('relatorios', function (Blueprint $table) {
            // Adicionar coluna deleted_at para SoftDeletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relatorios', function (Blueprint $table) {
            // Remover coluna deleted_at
            $table->dropSoftDeletes();
        });
    }
};
