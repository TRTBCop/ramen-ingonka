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
        Schema::create('mathml_images', function (Blueprint $table) {
            $table->char('md5_key', 32)->primary();
            $table->text('mml')->comment('mml(MathML)로 생성된 수식');
            $table->text('svg')->comment('svg 변환된 mml');
            $table->integer('width')->comment('svg 길이');
            $table->integer('height')->comment('svg 높이');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mathml_images');
    }
};
