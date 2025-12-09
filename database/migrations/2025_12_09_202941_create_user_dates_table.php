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
        Schema::create('user_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->index('fk_user_dates_user_id_users_id_idx');
            $table->string('month', 45)->nullable();
            $table->string('year', 45)->nullable();
            $table->integer('inovices_length')->nullable();
            $table->timestamps();
            $table->enum('type', ['invoice', 'evaluation', 'report'])->nullable()->default('invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_dates');
    }
};
