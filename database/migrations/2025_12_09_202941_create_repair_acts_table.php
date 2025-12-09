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
        Schema::create('repair_acts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_id')->nullable()->index('repair_acts_location_id_foreign');
            $table->unsignedBigInteger('device_type_id')->nullable()->index('repair_acts_device_type_id_foreign');
            $table->unsignedBigInteger('device_brand_id')->nullable()->index('repair_acts_device_brand_id_foreign');
            $table->unsignedBigInteger('repair_id')->nullable()->index('repair_acts_repair_id_foreign');
            $table->text('note')->nullable();
            $table->text('client_name')->nullable();
            $table->text('position')->nullable();
            $table->text('additional_information')->nullable();
            $table->string('uuid')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('repair_acts_user_id_foreign');
            $table->longText('signature')->nullable();
            $table->integer('is_mobile')->nullable()->default(0);
            $table->text('device_model')->nullable();
            $table->text('inventory_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_acts');
    }
};
