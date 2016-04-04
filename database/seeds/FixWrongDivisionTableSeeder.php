<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Models\ItemInventories;
use App\Models\Item;

class FixWrongDivisionTableSeeder extends Seeder
{
    public function run()
    {
    	$divisions = ['HPC CATEGORY', 'FOODS CATEGORY'];
        $inventories = ItemInventories::whereNotIn('division',$divisions)->get();

        foreach ($inventories as $inventory) {
        	$item = Item::where('sku_code',$inventory->sku_code)->first();
        	$inventory->division = $item->division->division;
        	$inventory->category = $item->category->category;
        	$inventory->category_long = $item->category->category_long;
        	$inventory->sub_category = $item->subcategory->sub_category;
        	$inventory->brand = $item->brand->brand;
        	$inventory->update();
        }
    }
}
