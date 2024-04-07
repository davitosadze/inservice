<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("client_name")->nullable();
            $table->string("identification_code")->nullable();
            $table->string("client_identification_code")->nullable();
            $table->string("legal_address")->nullable();
            $table->string("service_name")->nullable();
            $table->string("contract_service_type")->nullable();
            $table->date("contract_start_date")->nullable();
            $table->date("contract_end_date")->nullable();
            $table->bigInteger("contract_period")->nullable();
            $table->string("contract_status")->nullable();
            $table->string("contact_name")->nullable();
            $table->string("contact_number")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
