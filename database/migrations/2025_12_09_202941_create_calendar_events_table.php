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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('purchaser_id')->nullable();
            $table->timestamps();
            $table->bigInteger('id', true)->unique('calendar_events_un');
            $table->text('content')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('response_id')->nullable();

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
