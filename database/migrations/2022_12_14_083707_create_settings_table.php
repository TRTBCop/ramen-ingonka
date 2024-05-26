<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('group')->default('default')->comment('그룹명');
            $table->nullableMorphs('model');
            $table->string('name');
            $table->mediumText('value');
            $table->timestamps();
            $table->unique(['group', 'name']);
            $table->comment('환경설정');
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
