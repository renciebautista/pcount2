<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemTypeOnItemsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_items', function (Blueprint $table) {
            $table->integer('item_type_id')->unsigned()->after('item_id');
            $table->foreign('item_type_id')->references('id')->on('item_types');
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_items', function (Blueprint $table) {
            $table->dropForeign('store_items_item_type_id_foreign');
            $table->dropColumn(['item_type_id']);
        });
    }
}
