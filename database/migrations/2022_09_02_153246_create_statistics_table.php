<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_id')->constrained()->cascadeOnDelete();
            $table->char('country_code', '2')->unique();
            $table->integer('visit_count')->default('0');
        });
    }

    public function down()
    {
        Schema::dropIfExists('statistics');
    }
};
