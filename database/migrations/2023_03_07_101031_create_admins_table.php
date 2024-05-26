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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('관리자명');
            $table->string('access_id', 50)->unique()->comment('아이디');
            $table->string('password')->comment('비밀번호');
            $table->string('remember_token')->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('관리자');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
