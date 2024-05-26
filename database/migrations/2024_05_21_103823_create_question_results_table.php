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
        Schema::create('question_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->comment('student_id')->constrained();
            $table->foreignId('question_id')->comment('question_id')->constrained();
            $table->morphs('model');
            $table->integer('total_answers')->default(0)->comment('전체 답변 수');
            $table->integer('correct_answers')->default(0)->comment('정답 수');
            $table->integer('correct_percent')->default(0)->comment('정답률');
            $table->json('answers')->nullable()->comment('답안');
            $table->timestamp('completed_at')->nullable()->comment('문제완료일시');
            $table->timestamps();
            $table->comment('문제 결과 테이블');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_results');
    }
};
