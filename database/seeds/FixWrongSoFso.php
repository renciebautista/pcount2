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
            // echo $item->id . "\n";
        	if(!empty($item)){
        		$item->so = $temp_item->so;
        		$item->fso = $temp_item->fso;
        		$item->update();
        	}
        }

        $items = ItemInventories::where('sapc', 0)
            ->where('whpc', 0)
            ->where('whcs', 0)
            ->where('fso_multiplier', '>', 'ig')
            ->get();

        foreach ($items as $item) {
            $item->fso = $item->fso_multiplier;
            $item->fso_val = $item->fso * $item->lpbt;
            $item->update();
        }


    }
}
