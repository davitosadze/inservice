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
        Schema::create('category_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable()->index('fk_category_attributes_category_id_categories_id_idx');
            $table->string('name', 45)->nullable();
            $table->float('price', null, 0)->nullable();
            $table->string('item', 45)->nullable();
            $table->float('service_price', null, 0)->nullable()->default(0);
            $table->timestamps();
            $table->tinyInteger('category_type')->nullable()->default(0);
            $table->string('uuid', 70)->nullable()->unique('unique');
            $table->string('parent_uuid', 70)->nullable()->index('index_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attributes');
    }
};
