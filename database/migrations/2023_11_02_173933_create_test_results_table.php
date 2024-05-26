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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('test_id')->comment('tests.id')->constrained();
            $table->foreignId('student_id')->comment('students.id')->constrained();
            $table->smallInteger('point')->default(0)->comment('점수');
            $table->json('extra')->nullable()->comment('결과데이터');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->comment('진단평가결과');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
