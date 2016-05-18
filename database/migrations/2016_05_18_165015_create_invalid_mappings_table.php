<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvalidMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('premise_code');
            $table->string('customer_code');
            $table->string('store_code');
            $table->string('sku_code');
            $table->string('ig');
            $table->string('multiplier');
            $table->string('minstock');
            $table->string('type');
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
        Schema::drop('invalid_mappings');
    }
}
