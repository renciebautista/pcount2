<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\StoreInventories;
use App\Models\AssortmentInventories;
use App\Models\Store;

class UpdateStoreInvStorePriIdTableSeeder extends Seeder
{
    public function run()
    {
        $store_inventories = StoreInventories::all();
        foreach ($store_inventories as $inventory) {
        	$store = Store::where('store_code',$inventory->store_code)->first();
        	if(!empty($store)){
        		$inventory->store_pri_id = $store->id;
        		$inventory->update();
        	}
        	
        }

        $assortment_inventories = AssortmentInventories::all();
        foreach ($assortment_inventories as $inventory) {
        	$store = Store::where('store_code',$inventory->store_code)->first();
        	if(!empty($store)){
        		$inventory->store_pri_id = $store->id;
        		$inventory->update();
        	}
        	
        }
    }
}
