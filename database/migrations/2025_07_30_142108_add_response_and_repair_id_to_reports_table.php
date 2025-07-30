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
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('response_id')->nullable();
            $table->unsignedBigInteger('repair_id')->nullable();
            
            $table->foreign('response_id')->references('id')->on('responses')->onDelete('set null');
            $table->foreign('repair_id')->references('id')->on('repairs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['response_id']);
            $table->dropForeign(['repair_id']);
            $table->dropColumn(['response_id', 'repair_id']);
        });
    }
};
