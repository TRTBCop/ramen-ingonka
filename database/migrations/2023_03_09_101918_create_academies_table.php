<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('학원명');
            $table->string('zipcode', 8)->default('')->comment('우편번호');
            $table->string('address')->default('')->comment('주소');
            $table->string('address2')->default('')->comment('상세주소');
            $table->string('phone')->default('')->comment('연락처');
            $table->string('staff_phone')->default('')->comment('담당자연락처');
            $table->string('staff_name')->default('')->comment('담당자명');
            $table->string('staff_email')->default('')->comment('담당자이메일');
            $table->smallInteger('status')->default(0)->comment('상태');
            $table->json('extra')->nullable()->comment('확장컬럼');
            $table->text('manager_memo')->nullable()->comment('관리자메모');
            $table->timestamps();
            $table->softDeletes();

            $table->comment('학원');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academies');
    }
};
