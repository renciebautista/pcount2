<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('area');
            $table->string('enrollment_type');
            $table->string('distributor_code');
            $table->string('distributor');
            $table->string('store_id');
            $table->string('store_code');
            $table->string('store_code_psup');
            $table->string('store_name');
            $table->string('client_code');
            $table->string('client_name');
            $table->string('channel_code');
            $table->string('channel_name');
            $table->string('customer_code');
            $table->string('customer_name');
            $table->string('region_short_name');
            $table->string('region_name');
            $table->string('region_code');
            $table->string('agency_code');
            $table->string('agency');
            $table->string('username');
            $table->string('signature');
            $table->date('transaction_date');
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
        Schema::drop('store_inventories');
    }
}
