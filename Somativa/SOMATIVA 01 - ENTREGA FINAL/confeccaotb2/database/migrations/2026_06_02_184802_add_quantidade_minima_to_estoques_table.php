<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estoques', function (Blueprint $table) {
            $table->integer('quantidade_minima')->default(5)->after('quantidade');
        });
    }

    public function down(): void
    {
        Schema::table('estoques', function (Blueprint $table) {
            $table->dropColumn('quantidade_minima');
        });
    }
};
