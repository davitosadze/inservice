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
        Schema::create('purchasers', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->text('subj_name')->nullable();
            $table->text('subj_address')->nullable();
            $table->timestamps();
            $table->text('identification_num')->nullable();
            $table->tinyInteger('single')->nullable()->default(0);
            $table->text('description')->nullable();
            $table->integer('technical_time')->nullable()->default(0);
            $table->integer('cleaning_time')->nullable()->default(0);
            $table->date('first_review_date')->nullable();
            $table->date('technical_review_date')->nullable();
            $table->date('base_creation_date')->nullable();
            $table->text('first_review_description')->nullable();
            $table->text('technical_review_description')->nullable();
            $table->text('base_description')->nullable();
            $table->integer('arrival_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasers');
    }
};
