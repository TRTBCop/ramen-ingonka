<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_concept_texts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->comment('trainings.id')->constrained();
            $table->json('readings')->nullable()->comment('학습컨텐츠-읽기 JSON');
            $table->json('summarizations')->nullable()->comment('학습컨텐츠-요약 JSON');
            $table->json('reinforcements')->nullable()->comment('학습컨텐츠-다지기 JSON');
            $table->timestamps();
            $table->comment('트레이닝-개념훈련-세트관리');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_concept_texts');
    }
};
