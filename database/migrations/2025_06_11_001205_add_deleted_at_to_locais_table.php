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
        Schema::table('locais', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('data_atualizacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locais', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
