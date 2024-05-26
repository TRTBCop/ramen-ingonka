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
        Schema::create('board_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->comment('admins.id')->constrained();
            $table->tinyInteger('scope')->default(0)->comment('노출범위(비트연산)');
            $table->string('category')->default('')->comment('분류');
            $table->string('sub_category')->default('')->comment('세부분류');
            $table->string('title')->comment('제목');
            $table->text('contents')->comment('내용');
            $table->boolean('display_home')->default(false)->comment('홈화면표시유무');
            $table->string('display_home_title')->default('')->comment('홈화면표시 제목');
            $table->date('display_home_start_date')->nullable()->comment('홈화면표시 종료일');
            $table->date('display_home_end_date')->nullable()->comment('홈화면표시 시작일');
            $table->timestamp('published_at')->nullable()->comment('노출일시');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->comment('삭제일시');

            $table->comment('게시판-공지사항');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_notices');
    }
};
