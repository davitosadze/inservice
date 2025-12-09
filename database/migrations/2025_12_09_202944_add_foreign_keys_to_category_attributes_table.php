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
        Schema::table('category_attributes', function (Blueprint $table) {
            $table->foreign(['category_id'], 'fk_category_attributes_category_id_categories_id')->references(['id'])->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['parent_uuid'], 'fk_category_attributes_uuid_category_attributes_parent_uuid')->references(['uuid'])->on('category_attributes')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_attributes', function (Blueprint $table) {
            $table->dropForeign('fk_category_attributes_category_id_categories_id');
            $table->dropForeign('fk_category_attributes_uuid_category_attributes_parent_uuid');
        });
    }
};
