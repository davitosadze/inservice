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
        Schema::create('service_acts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_id')->nullable()->index('service_acts_location_id_foreign');
            $table->unsignedBigInteger('service_id')->nullable()->index('service_acts_service_id_foreign');
            $table->text('note')->nullable();
            $table->text('client_name')->nullable();
            $table->text('position')->nullable();
            $table->text('additional_information')->nullable();
            $table->string('uuid')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('service_acts_user_id_foreign');
            $table->longText('signature')->nullable();
            $table->timestamps();
            $table->integer('is_mobile')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_acts');
    }
};
