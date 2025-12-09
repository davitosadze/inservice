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
        Schema::table('report_items', function (Blueprint $table) {
            $table->foreign(['report_id'], 'fk_report_items_report_items_reports_id')->references(['id'])->on('reports')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_items', function (Blueprint $table) {
            $table->dropForeign('fk_report_items_report_items_reports_id');
        });
    }
};
