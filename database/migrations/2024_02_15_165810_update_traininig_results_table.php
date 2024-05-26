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
        Schema::table('training_results', function (Blueprint $table) {
            $table->integer('timer')->nullable()->after('training_id')->comment('소요 시간');
            $table->integer('no')->nullable()->after('timer')->comment('n번째 학습');
            $table->dropColumn('stage');
            $table->dropColumn('point');
            $table->dropColumn('total_questions');
            $table->dropColumn('total_correct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_results', function (Blueprint $table) {
            $table->dropColumn('timer');
            $table->dropColumn('no');
            $table->smallInteger('stage')->default(0)->comment('학습단계');
            $table->smallInteger('point')->default(0)->comment('점수');
            $table->smallInteger('total_questions')->default(0)->comment('문제수');
            $table->smallInteger('total_correct')->default(0)->comment('정답수');
        });
    }
};
