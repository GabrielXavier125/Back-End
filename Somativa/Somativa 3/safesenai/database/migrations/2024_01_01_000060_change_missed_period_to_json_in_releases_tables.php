<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('early_releases', function (Blueprint $table) {
            $table->dropColumn('missed_period');
        });
        Schema::table('early_releases', function (Blueprint $table) {
            $table->json('missed_periods')->nullable()->after('status');
        });

        Schema::table('late_entries', function (Blueprint $table) {
            $table->dropColumn('missed_period');
        });
        Schema::table('late_entries', function (Blueprint $table) {
            $table->json('missed_periods')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('early_releases', function (Blueprint $table) {
            $table->dropColumn('missed_periods');
            $table->tinyInteger('missed_period')->unsigned()->nullable()->after('status');
        });

        Schema::table('late_entries', function (Blueprint $table) {
            $table->dropColumn('missed_periods');
            $table->tinyInteger('missed_period')->unsigned()->nullable()->after('status');
        });
    }
};
