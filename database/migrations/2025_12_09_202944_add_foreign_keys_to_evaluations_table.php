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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreign(['purchaser_id'], 'fk_evaluations_user_id_purchasers_id')->references(['id'])->on('purchasers')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['user_id'], 'fk_evaluations_user_id_users_id')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign('fk_evaluations_user_id_purchasers_id');
            $table->dropForeign('fk_evaluations_user_id_users_id');
        });
    }
};
