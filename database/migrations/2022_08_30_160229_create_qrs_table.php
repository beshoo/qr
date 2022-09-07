<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qrs', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type');
            $table->string('thumbnail')->nullable();
            $table->json('json');
            $table->char('qr_id', 8)->unique()->charset('utf8')->collation('utf8_bin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qrs');
    }
};
