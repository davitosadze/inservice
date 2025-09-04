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
        Schema::create('attributables', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->unsignedInteger('attributable_id')->nullable();
            $table->string('attributable_type', 45)->nullable();
            $table->unsignedInteger('category_attribute_id')->nullable();
            $table->text('title')->nullable();
            $table->tinyInteger('is_special')->default(0);
            $table->integer('qty')->default(1);
            $table->float('price')->default(0);
            $table->float('calc')->default(0);
            $table->float('service_price')->default(0);
            $table->float('evaluation_calc')->nullable();
            $table->float('evaluation_price')->nullable();
            $table->float('evaluation_service_price')->nullable();
            $table->tinyInteger('single')->default(0);
            $table->integer('sort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributables');
    }
};
