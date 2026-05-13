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
        Schema::table('fornecedors', function (Blueprint $table) {
            if (! Schema::hasColumn('fornecedors', 'nome')) {
                $table->string('nome')->after('id');
            }

            if (! Schema::hasColumn('fornecedors', 'email')) {
                $table->string('email')->unique()->after('nome');
            }

            if (! Schema::hasColumn('fornecedors', 'telefone')) {
                $table->string('telefone')->nullable()->after('email');
            }

            if (! Schema::hasColumn('fornecedors', 'CNPJ')) {
                $table->string('CNPJ')->nullable()->after('telefone');
            }

            if (! Schema::hasColumn('fornecedors', 'endereco')) {
                $table->string('endereco')->nullable()->after('CNPJ');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedors', function (Blueprint $table) {
            $columns = ['endereco', 'CNPJ', 'telefone', 'email', 'nome'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('fornecedors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};