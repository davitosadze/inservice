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
        Schema::create('responses', function (Blueprint $table) {
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
            $table->timestamp('created_at')->nullable()->index('idx_responses_created_at');
            $table->timestamp('updated_at')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index('responses_user_id_foreign');
            $table->string('subject_name')->nullable();
            $table->string('subject_address')->nullable();
            $table->string('name')->nullable();
            $table->string('identification_num')->nullable();
            $table->unsignedBigInteger('system_one')->nullable()->index('responses_system_one_foreign');
            $table->unsignedBigInteger('system_two')->nullable()->index('responses_system_two_foreign');
            $table->integer('status')->nullable()->index('idx_responses_status');
            $table->integer('device_type')->nullable()->default(0);
            $table->integer('on_repair')->nullable()->default(0);
            $table->integer('by_client')->nullable()->default(0);
            $table->timestamp('end_time')->nullable();
            $table->integer('type')->nullable()->default(1);
            $table->bigInteger('manager_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
