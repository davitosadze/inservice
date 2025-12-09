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
        Schema::table('attributables', function (Blueprint $table) {
            $table->foreign(['category_attribute_id'], 'fk_attributables_category_attribute_id_category_attributes_id')->references(['id'])->on('category_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['attributable_id'], 'fk_attributables_category_attribute_id_evaluation_id')->references(['id'])->on('evaluations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributables', function (Blueprint $table) {
            $table->dropForeign('fk_attributables_category_attribute_id_category_attributes_id');
            $table->dropForeign('fk_attributables_category_attribute_id_evaluation_id');
        });
    }
};
