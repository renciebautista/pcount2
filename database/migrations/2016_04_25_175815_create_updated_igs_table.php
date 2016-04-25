<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\StoreItem;
use App\Models\UpdatedIg;
class CreateUpdatedIgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updated_igs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_code');
            $table->string('sku_code');   
            $table->integer('min_stock');
            $table->integer('ig');
            $table->timestamps();    
        });

        $updated = StoreItem::with('store')
            ->with('item')
            ->where('ig_updated', 1)->get();
        foreach ($updated as $row) {
            UpdatedIg::create(['store_code' => $row->store->store_code, 
                'sku_code' => $row->item->sku_code, 
                'min_stock' => $row->min_stock,
                'ig' => $row->ig]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('updated_igs');
    }
}
