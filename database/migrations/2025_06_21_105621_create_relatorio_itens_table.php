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
            $table->integer('relatorio_id')->unsigned();
            $table->integer('equipamento_id')->unsigned();
            $table->text('descricao_equipamento');
            $table->text('observacoes')->nullable();
            $table->enum('status_item', ['pendente', 'em_andamento', 'concluido'])->default('pendente');
            $table->integer('ordem')->default(1);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('relatorio_id')->references('id')->on('relatorios')->onDelete('cascade');
            $table->foreign('equipamento_id')->references('id')->on('equipamentos');
            
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
