<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_curriculum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->comment('students.id')->constrained();
            $table->foreignId('curriculum_id')->comment('curricula.id')->constrained();
            $table->json('extra')->nullable()->comment('확장');
            $table->timestamp('completed_at')->nullable()->comment('학습완료일시(전체)');
            $table->timestamps();
            $table->comment('학생-학습현황');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_curriculum');
    }
};
