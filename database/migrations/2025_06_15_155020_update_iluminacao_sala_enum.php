<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alterar o enum para incluir as novas opções
        DB::statement("ALTER TABLE inspecoes_gerador MODIFY COLUMN iluminacao_sala ENUM('Sim', 'Não', 'Normal', 'Anormal') NULL");
        
        // Atualizar registros existentes
        DB::table('inspecoes_gerador')
            ->where('iluminacao_sala', 'Sim')
            ->update(['iluminacao_sala' => 'Normal']);
            
        DB::table('inspecoes_gerador')
            ->where('iluminacao_sala', 'Não')
            ->update(['iluminacao_sala' => 'Anormal']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter os registros
        DB::table('inspecoes_gerador')
            ->where('iluminacao_sala', 'Normal')
            ->update(['iluminacao_sala' => 'Sim']);
            
        DB::table('inspecoes_gerador')
            ->where('iluminacao_sala', 'Anormal')
            ->update(['iluminacao_sala' => 'Não']);
            
        // Reverter o enum
        DB::statement("ALTER TABLE inspecoes_gerador MODIFY COLUMN iluminacao_sala ENUM('Sim', 'Não') NULL");
    }
};
