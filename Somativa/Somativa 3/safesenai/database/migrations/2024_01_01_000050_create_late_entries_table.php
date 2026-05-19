<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('late_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('restrict');
            $table->foreignId('coordinator_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('reason');
            $table->text('observation')->nullable();
            $table->enum('status', ['waiting_teacher', 'confirmed', 'cancelled'])->default('waiting_teacher');
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('late_entries');
    }
};
