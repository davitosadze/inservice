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
        Schema::create('repairs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content')->nullable();
            $table->text('exact_location')->nullable();
            $table->text('job_description')->nullable();
            $table->string('requisites')->nullable();
            $table->string('inventory_number')->nullable();
            $table->string('time')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('purchaser_id')->nullable();
            $table->bigInteger('performer_id')->nullable();
            $table->bigInteger('region_id')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('repairs_user_id_foreign');
            $table->integer('status')->nullable()->default(0);
            $table->integer('on_repair')->nullable()->default(0);
            $table->integer('device_type')->nullable()->default(0);
            $table->string('subject_name')->nullable();
            $table->string('subject_address')->nullable();
            $table->string('name')->nullable();
            $table->string('identification_num')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('repair_device_id')->nullable()->index('repairs_repair_device_id_foreign');
            $table->integer('from_id')->nullable();
            $table->string('from')->nullable();
            $table->integer('type')->nullable()->default(1);
            $table->boolean('standby_mode')->nullable()->default(false);
            $table->dateTime('estimated_arrival_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
