<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academy_id')->nullable()->comment('academies.id')->constrained();
            $table->string('access_id')->unique()->comment('아이디');
            $table->string('password')->comment('비밀번호');
            $table->string('address')->default('')->comment('주소');
            $table->string('school_name')->default('')->comment('학교명');
            $table->smallInteger('grade')->default(0)->comment('학년');
            $table->smallInteger('term')->default(0)->comment('학기');
            $table->string('name')->default('')->comment('학생 이름');
            $table->string('phone')->default('')->comment('학생 연락처');
            $table->string('parents_name')->default('')->comment('학부모 이름');
            $table->string('parents_phone')->default('')->comment('학부모 연락처');
            $table->date('birth_date')->nullable()->comment('생년월일');
            $table->text('manager_memo')->nullable()->comment('관리자 메모');
            $table->date('service_start_date')->nullable()->comment('서비스시작일');
            $table->date('service_end_date')->nullable()->comment('서비스종료일');
            $table->tinyInteger('marketing_consent')->default(0)->comment('마케팅 동의');
            $table->timestamp('last_login_at')->nullable()->comment('마지막로그인일시');
            $table->string('kakao_id', 100)->nullable()->comment('소셜로그인(카카오)');
            $table->string('naver_id', 100)->nullable()->comment('소셜로그인(네이버)');
            $table->smallInteger('status')->default(0)->comment('상태');
            $table->json('extra')->nullable()->comment('확장컬럼');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->comment('학생');
            $table->index('naver_id');
            $table->index('kakao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
