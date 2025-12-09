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
        Schema::create('report_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('report_id')->nullable()->index('fk_report_items_report_items_reports_id_idx');
            $table->string('title', 45)->nullable();
            $table->text('value')->nullable();
            $table->timestamps();
            $table->string('uuid', 70)->nullable()->unique('uuid_unique');
            $table->string('parent_uuid', 70)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_items');
    }
};
