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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->text("content")->nullable();
            $table->text("exact_location")->nullable();
            $table->text("job_description")->nullable();
            $table->string("requisites")->nullable();
            $table->string("inventory_number")->nullable();
            $table->string("time")->nullable();
            $table->date("date")->nullable();
            $table->bigInteger('purchaser_id')->nullable();
            $table->bigInteger('performer_id')->nullable();
            $table->bigInteger('region_id')->nullable();
            $table->unsignedInteger("user_id")->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer("status")->default(0)->nullable();
            $table->integer("on_repair")->default(0)->nullable();
            $table->integer("device_type")->default(0)->nullable();

            $table->string("subject_name")->nullable();
            $table->string("subject_address")->nullable();
            $table->string("name")->nullable();
            $table->string("identification_num")->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
