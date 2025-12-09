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
        Schema::create('attributables', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('attributable_id')->nullable()->index('fk_attributables_attributable_id_category_attributes_id_idx');
            $table->string('attributable_type', 45)->nullable();
            $table->unsignedInteger('category_attribute_id')->nullable()->index('fk_attributables_category_attribute_id_category_attributes__idx');
            $table->text('title')->nullable();
            $table->tinyInteger('is_special')->nullable()->default(0);
            $table->integer('qty')->nullable()->default(1);
            $table->float('price', null, 0)->nullable()->default(0);
            $table->float('calc', null, 0)->nullable()->default(0);
            $table->float('service_price', null, 0)->nullable()->default(0);
            $table->float('evaluation_calc', null, 0)->nullable();
            $table->float('evaluation_price', null, 0)->nullable();
            $table->float('evaluation_service_price', null, 0)->nullable();
            $table->tinyInteger('single')->nullable()->default(0);
            $table->integer('sort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributables');
    }
};
