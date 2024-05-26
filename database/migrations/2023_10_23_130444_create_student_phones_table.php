<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_phones', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->comment('students.phone');
            $table->string('code', 4)->comment('인증번호');
            $table->tinyInteger('verified')->unsigned()->default(0);
            $table->foreignId('student_id')->nullable()->unsigned()->comment('students.id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_phones');
    }
};
