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
        Schema::create('motores', function (Blueprint $table) {
            $table->id();
            $table->string('tag', 100)->nullable()->comment('Tag de identificação do motor');
            $table->string('equipment', 255)->nullable()->comment('Nome do equipamento');
            $table->string('frame_manufacturer', 255)->nullable()->comment('Fabricante do frame');
            $table->decimal('power_kw', 10, 2)->nullable()->comment('Potência em kW');
            $table->decimal('power_cv', 10, 2)->nullable()->comment('Potência em CV');
            $table->integer('rotation')->nullable()->comment('Rotação RPM');
            $table->decimal('rated_current', 10, 2)->nullable()->comment('Corrente nominal');
            $table->decimal('configured_current', 10, 2)->nullable()->comment('Corrente configurada');
            $table->string('equipment_type', 255)->nullable()->comment('Tipo do equipamento');
            $table->string('manufacturer', 255)->nullable()->comment('Fabricante');
            $table->string('stock_reserve', 255)->nullable()->comment('Estoque reserva');
            $table->string('location', 255)->nullable()->comment('Localização');
            $table->string('photo', 255)->nullable()->comment('Caminho da foto');
            $table->string('storage', 255)->nullable()->comment('Armazenamento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motores');
    }
};
