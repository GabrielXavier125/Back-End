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
        Schema::table('estoques', function (Blueprint $table) {
            if (! Schema::hasColumn('estoques', 'produto_id')) {
                $table->foreignId('produto_id')->after('id')->constrained('produtos')->cascadeOnDelete();
                $table->unique('produto_id');
            }

            if (! Schema::hasColumn('estoques', 'quantidade')) {
                $table->unsignedInteger('quantidade')->default(0)->after('produto_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estoques', function (Blueprint $table) {
            if (Schema::hasColumn('estoques', 'quantidade')) {
                $table->dropColumn('quantidade');
            }

            if (Schema::hasColumn('estoques', 'produto_id')) {
                $table->dropUnique('estoques_produto_id_unique');
                $table->dropConstrainedForeignId('produto_id');
            }
        });
    }
};
