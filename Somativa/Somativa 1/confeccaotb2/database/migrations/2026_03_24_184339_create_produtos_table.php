<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('produtos')) {
            Schema::create('produtos', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->text('descricao')->nullable();
                $table->decimal('preco', 10, 2);
                $table->foreignId('fornecedor_id')->constrained('fornecedors')->cascadeOnDelete();
                $table->timestamps();
            });

            return;
        }

        Schema::table('produtos', function (Blueprint $table) {
            if (! Schema::hasColumn('produtos', 'nome')) {
                $table->string('nome')->after('id');
            }

            if (! Schema::hasColumn('produtos', 'descricao')) {
                $table->text('descricao')->nullable()->after('nome');
            }

            if (! Schema::hasColumn('produtos', 'preco')) {
                $table->decimal('preco', 10, 2)->after('descricao');
            }

            if (! Schema::hasColumn('produtos', 'fornecedor_id')) {
                $table->foreignId('fornecedor_id')->nullable()->after('preco');
            }
        });

        $hasFornecedorForeignKey = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', 'produtos')
            ->where('COLUMN_NAME', 'fornecedor_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();

        if (! $hasFornecedorForeignKey && Schema::hasColumn('produtos', 'fornecedor_id')) {
            Schema::table('produtos', function (Blueprint $table) {
                $table->foreign('fornecedor_id')->references('id')->on('fornecedors')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
