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
            $table->string('store_name');
            $table->string('sku_code');
            $table->string('description'); 
            $table->string('division');  
            $table->string('category');  
            $table->string('sub_category');  
            $table->string('brand');  
            $table->integer('conversion');    
            $table->integer('min_stock');  
            $table->integer('fso_multiplier');  
            $table->decimal('lpbt', 20,16);
            $table->integer('ig');
            $table->timestamps();    
        });

        $updated = StoreItem::with('store')
            ->with('item')
            ->where('ig_updated', 1)->get();
        foreach ($updated as $row) {
            UpdatedIg::create(['store_code' => $row->store->store_code, 
                'store_name' => $row->store->store_name, 
                'sku_code' => $row->item->sku_code, 
                'description' => $row->item->description, 
                'division' => $row->item->division->division, 
                'category' => $row->item->category->category, 
                'sub_category' => $row->item->subcategory->sub_category, 
                'brand' => $row->item->brand->brand, 
                'conversion' => $row->item->conversion,
                'fso_multiplier' => $row->fso_multiplier, 
                'min_stock' => $row->min_stock,
                'lpbt' => $row->item->lpbt, 
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
