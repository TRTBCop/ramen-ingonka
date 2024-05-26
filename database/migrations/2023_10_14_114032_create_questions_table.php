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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->nullable()->comment('curricula_id')->constrained();
            $table->smallInteger('level')->default(1)->comment('난이도');
            $table->text('question')->nullable()->comment('문제풀이');
            $table->text('inquiry')->comment('발문');
            $table->text('options')->nullable()->comment('보기');
            $table->json('answers')->comment('답안');
            $table->text('explanation')->nullable()->comment('해설');
            $table->timestamp('published_at')->nullable()->comment('노출일시');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('문제');
        });

        Schema::create('questionables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->comment('questions.id')->constrained()->cascadeOnDelete();
            $table->morphs('model');
            $table->json('extra')->nullable()->comment('확장컬럼');
            $table->timestamps();
            $table->comment('문제관계테이블');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionables');
        Schema::dropIfExists('questions');
    }
};
