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
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->index('fk_reports_user_id_users_id_idx');
            $table->string('title', 45)->nullable();
            $table->string('uuid', 45)->nullable();
            $table->text('subject_name')->nullable();
            $table->text('subject_address')->nullable();
            $table->timestamps();
            $table->text('name')->nullable();
            $table->text('identification_num')->nullable();
            $table->unsignedBigInteger('response_id')->nullable();
            $table->unsignedBigInteger('repair_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
