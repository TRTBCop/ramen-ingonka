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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->comment('curricula.id')->constrained();
            $table->smallInteger('stage')->default(0)->comment('학습단계');
            $table->json('contents')->nullable()->comment('학습컨텐츠 Json');
            $table->timestamp('published_at')->nullable()->comment('노출일시');
            $table->timestamps();
            $table->comment('트레이닝컨텐츠');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
