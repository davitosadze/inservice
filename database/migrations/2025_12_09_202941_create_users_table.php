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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('inter_password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('manager_type')->nullable()->default(0);
            $table->text('job_description')->nullable();
            $table->string('position')->nullable();
            $table->string('expo_token')->nullable();
            $table->integer('status')->nullable()->default(1);
            $table->boolean('responses_limited')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
