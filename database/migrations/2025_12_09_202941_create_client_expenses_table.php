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
        Schema::create('client_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount')->nullable()->default(0);
            $table->unsignedBigInteger('client_id')->nullable()->index('client_expenses_client_id_foreign');
            $table->timestamps();
            $table->string('uuid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_expenses');
    }
};
