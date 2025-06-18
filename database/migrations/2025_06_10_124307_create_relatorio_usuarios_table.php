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
        Schema::create('relatorio_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relatorio_id')->constrained('relatorios')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('permissao', ['leitura', 'edicao'])->default('edicao');
            $table->timestamp('atribuido_em')->useCurrent();
            $table->foreignId('atribuido_por')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicatas
            $table->unique(['relatorio_id', 'user_id']);
            
            // Índices
            $table->index('relatorio_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorio_usuarios');
    }
};
