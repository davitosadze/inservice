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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('purchaser_id')->nullable();
            $table->string('p1', 45)->nullable();
            $table->string('p2', 45)->nullable();
            $table->string('p3', 45)->nullable();
            $table->string('p4', 45)->nullable();
            $table->string('p5', 45)->nullable();
            $table->timestamps();
            $table->enum('type', ['invoice', 'evaluation'])->default('invoice');
            $table->text('title')->nullable();
            $table->string('uuid', 17)->nullable();
            $table->string('parent_uuid', 17)->nullable();
            $table->integer('warranty_period')->default(0);
            $table->unsignedBigInteger('response_id')->nullable();
            $table->unsignedBigInteger('repair_id')->nullable();
            
            $table->index('user_id', 'fk_evaluations_user_id_users_id_idx');
            $table->index('purchaser_id', 'fk_evaluations_user_id_purchasers_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};
