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
        Schema::create('repair_acts', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger("location_id")->nullable();
            $table->foreign('location_id')->references('id')->on('locations');


            $table->unsignedBigInteger("device_type_id")->nullable();
            $table->foreign('device_type_id')->references('id')->on('device_types');

            $table->unsignedBigInteger("device_brand_id")->nullable();
            $table->foreign('device_brand_id')->references('id')->on('device_brands');


            $table->unsignedBigInteger("repair_id")->nullable();
            $table->foreign('repair_id')->references('id')->on('repairs')->onDelete('cascade');

            $table->text("note")->nullable();
            $table->text("client_name")->nullable();
            $table->text("position")->nullable();
            $table->text("additional_information")->nullable();
            $table->string("uuid")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text("signature")->nullable();
            $table->integer('is_mobile')->default(0)->nullable();

            $table->text("device_model")->nullable();
            $table->text("inventory_code")->nullable();

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
        Schema::dropIfExists('repair_acts');
    }
};
