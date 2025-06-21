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
        Schema::create('relatorio_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relatorio_id')->constrained('relatorios')->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained('equipamentos');
            $table->text('descricao_equipamento');
            $table->text('observacoes')->nullable();
            $table->enum('status_item', ['pendente', 'em_andamento', 'concluido'])->default('pendente');
            $table->integer('ordem')->default(1); // Para ordenar os itens
            $table->timestamps();
            
            // Índices para performance
            $table->index(['relatorio_id', 'ordem']);
            $table->index('equipamento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorio_itens');
    }
};
