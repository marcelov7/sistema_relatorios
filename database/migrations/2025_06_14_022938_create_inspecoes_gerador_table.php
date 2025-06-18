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
        Schema::create('inspecoes_gerador', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('data');
            $table->string('colaborador', 255)->nullable();
            $table->string('nivel_oleo', 50)->nullable();
            $table->string('nivel_agua', 50)->nullable();
            $table->decimal('tensao_sync_gerador', 10, 2)->nullable();
            $table->decimal('tensao_sync_rede', 10, 2)->nullable();
            $table->decimal('temp_agua', 10, 2)->nullable();
            $table->decimal('pressao_oleo', 10, 2)->nullable();
            $table->decimal('frequencia', 10, 2)->nullable();
            $table->decimal('tensao_a', 10, 2)->nullable();
            $table->decimal('tensao_b', 10, 2)->nullable();
            $table->decimal('tensao_c', 10, 2)->nullable();
            $table->integer('rpm')->nullable();
            $table->decimal('tensao_bateria', 10, 2)->nullable();
            $table->decimal('tensao_alternador', 10, 2)->nullable();
            $table->enum('combustivel_50', ['Sim', 'Não'])->nullable();
            $table->enum('iluminacao_sala', ['Sim', 'Não'])->nullable();
            $table->text('observacao')->nullable();
            $table->tinyInteger('ativo')->default(1);
            $table->datetime('criado_em')->useCurrent();
            $table->datetime('atualizado_em')->useCurrent()->useCurrentOnUpdate();
            
            // Índices
            $table->index(['data', 'ativo']);
            $table->index('colaborador');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspecoes_gerador');
    }
};
