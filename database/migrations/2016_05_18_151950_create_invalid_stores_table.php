<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvalidStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('area');
            $table->string('enrollment_type');
            $table->string('distributor_code');
            $table->string('distributor');
            $table->string('storeid');
            $table->string('store_code');
            $table->string('store_code_psup');
            $table->string('store_name');
            $table->string('client_code');
            $table->string('client_name');
            $table->string('channel_code');
            $table->string('channel_name');
            $table->string('customer_code');
            $table->string('customer');
            $table->string('region_short');
            $table->string('region_name');
            $table->string('region_code');
            $table->string('fms');
            $table->string('fms_username');
            $table->string('agency_code');
            $table->string('agency_name');
            $table->string('lead_refillers');
            $table->string('username');
            $table->string('status');
            $table->string('remarks');
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
        Schema::drop('invalid_stores');
    }
}
