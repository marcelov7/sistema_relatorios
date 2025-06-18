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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('mensagem');
            $table->enum('tipo', ['relatorio_criado', 'relatorio_atualizado', 'relatorio_concluido', 'relatorio_atribuido', 'sistema']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('relatorio_id')->nullable()->constrained('relatorios')->onDelete('cascade');
            $table->boolean('lida')->default(false);
            $table->timestamp('lida_em')->nullable();
            $table->json('dados_extras')->nullable(); // Para armazenar dados adicionais da notificação
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'lida']);
            $table->index(['tipo', 'created_at']);
            $table->index('relatorio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
