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
        Schema::table('board_notices', function (Blueprint $table) {
            $table->dropColumn(['display_home', 'display_home_title', 'display_home_start_date', 'display_home_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_notices', function (Blueprint $table) {
            $table->boolean('display_home')->default(false)->comment('홈화면표시유무');
            $table->string('display_home_title')->default('')->comment('홈화면표시 제목');
            $table->date('display_home_start_date')->nullable()->comment('홈화면표시 종료일');
            $table->date('display_home_end_date')->nullable()->comment('홈화면표시 시작일');
        });
    }
};
