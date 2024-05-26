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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('model');
            $table->string('subject')->default('')->comment('제목');
            $table->string('send_phone', 13)->comment('발신번호');
            $table->string('dest_phone', 13)->comment('수신번호');
            $table->string('template_code', 20)->default('')->comment('알림톡템플릿코드');
            $table->text('msg')->comment('내용');
            $table->boolean('is_debug')->default(true)->comment('디버그유무');
            $table->timestamp('created_at')->useCurrent();

            $table->comment('sms 발송내역');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
