<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_concept_text_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->comment('student_id')->constrained();
            $table->foreignId('step_result_id')->comment('step_result_id')->constrained();
            $table->foreignId('training_concept_text_id')->comment('training_concept_text_id')->constrained();
            $table->integer('total_answers')->default(0)->comment('전체 답변 수');
            $table->integer('correct_answers')->default(0)->comment('정답 수');
            $table->integer('correct_percent')->default(0)->comment('정답률');
            $table->boolean('is_reading_completed')->default(false)->comment('개념 읽기 완료 여부');
            $table->timestamp('completed_at')->nullable()->comment('지문완료일시');
            $table->timestamps();
            $table->comment('지문 결과 테이블');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_concept_text_results');
    }
};
