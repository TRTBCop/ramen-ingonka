<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTrainingResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_results', function (Blueprint $table) {
            $table->integer('score')->after('round')->default(0)->comment('점수');
            $table->integer('total_answers')->after('round')->default(0)->comment('전체 답변 수');
            $table->integer('correct_answers')->after('round')->default(0)->comment('정답 수');
            $table->integer('correct_percent')->after('round')->default(0)->comment('정답률');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_results', function (Blueprint $table) {
            $table->dropColumn('score');
            $table->dropColumn('correct_answers');
            $table->dropColumn('total_answers');
            $table->dropColumn('correct_percent');
        });
    }
}
