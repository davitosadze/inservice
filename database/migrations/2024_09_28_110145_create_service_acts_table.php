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
        Schema::create('service_acts', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger("location_id")->nullable();
            $table->foreign('location_id')->references('id')->on('locations');


            $table->unsignedBigInteger("service_id")->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->text("note")->nullable();
            $table->text("client_name")->nullable();
            $table->text("position")->nullable();
            $table->text("additional_information")->nullable();
            $table->string("uuid")->nullable();
            $table->unsignedInteger("user_id")->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text("signature")->nullable();
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
        Schema::dropIfExists('service_acts');
    }
};
