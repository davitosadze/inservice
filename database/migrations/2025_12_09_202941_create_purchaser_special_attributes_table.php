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
        Schema::create('purchaser_special_attributes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('category_attribute_id')->nullable();
            $table->integer('purchaser_id')->nullable();
            $table->timestamps();
            $table->json('json')->nullable();

            $table->unique(['category_attribute_id', 'purchaser_id'], 'test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaser_special_attributes');
    }
};
