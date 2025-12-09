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
        Schema::create('attributes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cid');
            $table->integer('mid');
            $table->integer('pid');
            $table->text('title');
            $table->double('price', null, 0);
            $table->double('service_price', null, 0);
            $table->double('count', null, 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
