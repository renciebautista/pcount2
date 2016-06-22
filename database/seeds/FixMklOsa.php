<?php

use Illuminate\Database\Seeder;
use App\Models\StoreInventories;
use App\Models\ItemInventories;

class FixMklOsa extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('item_inventories')
    		->where('conversion', '>', 0)
            ->update(['min_stock' => 2, 'oos' => 1, 'osa' => 0]);

    	$areas = ['MDC', 'ROSE PHARMACY', '360 PHARMACY', '360 DRUG', 'ST. JOSEPH DRUG', 'SOUTH STAR DRUG'];
    	$stores = StoreInventories::select('id')
    		->whereIn('area', $areas)
    		->get();

    	foreach ($stores as $item) {
    		ItemInventories::where('store_inventory_id', $item->id)
    			->update(['min_stock' => 3]);
    	}

    	DB::statement("update `item_inventories` set `oos` = 0, `osa` = 1 where `sapc` > min_stock");

    }
}
