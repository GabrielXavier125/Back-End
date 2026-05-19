<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('early_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('restrict');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('gatekeeper_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('reason');
            $table->text('observation')->nullable();
            $table->enum('status', ['waiting_gate', 'released', 'cancelled'])->default('waiting_gate');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('early_releases');
    }
};
