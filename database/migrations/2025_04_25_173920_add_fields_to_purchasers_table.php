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
        Schema::table('purchasers', function (Blueprint $table) {
            $table->date('first_review_date')->nullable();
            $table->date('technical_review_date')->nullable();
            $table->date('base_creation_date')->nullable();

            $table->text('first_review_description')->nullable();
            $table->text('technical_review_description')->nullable();
            $table->text('base_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchasers', function (Blueprint $table) {
            //
        });
    }
};
