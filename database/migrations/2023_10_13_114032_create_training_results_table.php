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
        Schema::create('training_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->comment('curriculum.id')->constrained();
            $table->foreignId('student_id')->comment('students.id')->constrained();
            $table->foreignId('training_id')->comment('trainings.id')->constrained();
            $table->smallInteger('stage')->default(0)->comment('학습단계');
            $table->smallInteger('point')->default(0)->comment('점수');
            $table->smallInteger('total_questions')->default(0)->comment('문제수');
            $table->smallInteger('total_correct')->default(0)->comment('정답수');
            $table->json('extra')->nullable()->comment('결과 데이터');
            $table->timestamp('completed_at')->nullable()->comment('학습완료일시(트레이닝별)');
            $table->timestamps();
            $table->comment('트레이닝 결과저장');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_results');
    }
};
