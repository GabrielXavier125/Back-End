<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: make teacher_id nullable (teacher now confirms AFTER coordinator creates)
        Schema::table('early_releases', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('restrict');
        });

        // Step 2: add coordinator_id and teacher_confirmed_at
        Schema::table('early_releases', function (Blueprint $table) {
            $table->unsignedBigInteger('coordinator_id')->nullable()->after('student_id');
            $table->foreign('coordinator_id')->references('id')->on('users')->onDelete('restrict');
            $table->timestamp('teacher_confirmed_at')->nullable()->after('released_at');
        });

        // Step 3: migrate existing records — old teacher_id becomes coordinator_id
        DB::table('early_releases')->whereNotNull('teacher_id')->update([
            'coordinator_id' => DB::raw('teacher_id'),
        ]);
        // Records pending gate had no teacher confirmation in new flow
        DB::table('early_releases')->where('status', 'waiting_gate')->update([
            'teacher_id' => null,
        ]);

        // Step 4: expand status enum
        DB::statement("ALTER TABLE early_releases MODIFY COLUMN status ENUM('waiting_teacher','waiting_gate','released','cancelled') NOT NULL DEFAULT 'waiting_teacher'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE early_releases MODIFY COLUMN status ENUM('waiting_gate','released','cancelled') NOT NULL DEFAULT 'waiting_gate'");

        Schema::table('early_releases', function (Blueprint $table) {
            $table->dropForeign(['coordinator_id']);
            $table->dropColumn(['coordinator_id', 'teacher_confirmed_at']);
        });

        Schema::table('early_releases', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->unsignedBigInteger('teacher_id')->nullable(false)->change();
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('restrict');
        });
    }
};
