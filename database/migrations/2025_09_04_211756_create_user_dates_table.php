<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('month', 45)->nullable();
            $table->string('year', 45)->nullable();
            $table->integer('inovices_length')->nullable();
            $table->timestamps();
            $table->enum('type', ['invoice', 'evaluation', 'report'])->default('invoice');
            
            $table->index('user_id', 'fk_user_dates_user_id_users_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_dates');
    }
};
