<?php

use Illuminate\Database\Seeder;

use App\Models\ItemInventories;
use App\Models\TempInventories;
use App\Models\StoreInventories;

class FixWrongSoFso extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$temp_items = TempInventories::all();
        foreach ($temp_items as $temp_item) {
        	$item = ItemInventories::where('store_inventory_id', $temp_item->store_inventory_id)
        		->where('other_barcode', $temp_item->other_barcode)
        		->first();
        	if(!empty($temp_item)){
        		$item->so = $temp_item->so;
        		$item->fso = $temp_item->fso;
        		$item->update();
        	}
        }

        // StoreInventories::where('fixed', 1)->update(['fixed' => 0]);
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('item_inventories')->truncate();
        // $temp_items = TempInventories::all();
        // foreach ($temp_items as $temp_item) {
        // 	ItemInventories::insert([
        //                 'store_inventory_id' => $temp_item->store_inventory_id,
        //                 'division' => $temp_item->division,
        //                 'category' => $temp_item->category,
        //                 'category_long' => $temp_item->category_long,
        //                 'sub_category' => $temp_item->sub_category,
        //                 'brand' => $temp_item->brand,
        //                 'sku_code' => $temp_item->sku_code,
        //                 'other_barcode' => $temp_item->other_barcode,
        //                 'description' => $temp_item->description,
        //                 'description_long' => $temp_item->description_long,
        //                 'lpbt' => $temp_item->lpbt,
        //                 'conversion' => $temp_item->conversion,
        //                 'ig' => $temp_item->ig,
        //                 'fso_multiplier' => $temp_item->fso_multiplier,
        //                 'sapc' => $temp_item->sapc,
        //                 'whpc' => $temp_item->whpc,
        //                 'whcs' => $temp_item->whcs,
        //                 'so' => $temp_item->so,
        //                 'fso' => $temp_item->fso,
        //                 'fso_val' => $temp_item->fso_val]);
        // }
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // DB::table('temp_inventories')->truncate();


    }
}
