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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('')->comment('학년/학기');
            $table->json('contents')->nullable()->comment('진단평가 컨텐츠');
            $table->timestamp('published_at')->nullable()->comment('노출일시');
            $table->timestamps();
            $table->comment('진단평가컨텐츠');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
