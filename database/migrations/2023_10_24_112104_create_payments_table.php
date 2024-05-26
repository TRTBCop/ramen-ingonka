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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('trd_no', 40)->comment('PG 거래번호');
            $table->char('method', 2)->default('')->comment('결제방법');
            $table->string('od_id', 40)->comment('주문번호');
            $table->string('od_name', 40)->default('')->comment('주문자명');
            $table->integer('amount')->default(0)->comment('결제금액');
            $table->integer('cancel_amount')->default(0)->comment('취소금액');
            $table->tinyInteger('status')->default(0)->comment('결제상태');
            $table->json('extra')->nullable()->comment('기록용');
            $table->timestamp('canceled_at')->nullable()->comment('취소일시');
            $table->timestamp('approved_at')->nullable()->comment('결제일시');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->comment('삭제일시');
            $table->comment('결제정보');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
