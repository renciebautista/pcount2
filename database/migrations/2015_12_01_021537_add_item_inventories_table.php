<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_inventory_id')->unsigned();
            $table->foreign('store_inventory_id')->references('id')->on('store_inventories');
            $table->string('division');
            $table->string('category');
            $table->string('category_long');
            $table->string('sub_category');
            $table->string('brand');
            $table->string('sku_code');
            $table->string('other_barcode');
            $table->string('description');
            $table->string('description_long');
            $table->decimal('lpbt', 20,16);
            $table->integer('conversion');
            $table->integer('ig');
            $table->integer('fso_multiplier');
            $table->integer('sapc');
            $table->integer('whpc');
            $table->integer('whcs');
            $table->integer('so');
            $table->integer('fso');
            $table->decimal('fso_val', 20,16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_inventories');
    }
}
