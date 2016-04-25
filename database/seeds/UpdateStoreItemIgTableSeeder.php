<?php

use Illuminate\Database\Seeder;
use App\Models\UpdatedIg;
use App\Models\Store;
use App\Models\Item;
use App\Models\StoreItem;

class UpdateStoreItemIgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $updated_igs = UpdatedIg::all();
        foreach ($updated_igs as $row) {
        	$store = Store::where('store_code',$row->store_code)->first();
        	if(!empty($store)){
        		$item = Item::where('sku_code', $row->sku_code)->first();
        		if(!empty($item)){
        			StoreItem::where('store_id', $store->id)
        				->where('item_id', $item->id)
        				->update(['ig' => $row->ig]);
        		}
        	}
        }
    }
}
