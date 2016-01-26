<?php

use Illuminate\Database\Seeder;

use App\Models\ItemInventories;
use App\Models\TempInventories;
use App\Models\StoreInventories;
use App\Models\Store;
use App\Models\Item;


class FixItemInventories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        // DB::table('item_inventories')->truncate();

        foreach (StoreInventories::where('fixed',0)->get() as $inventory) {
            $inventory_items = ItemInventories::where('store_inventory_id',$inventory->id)->get();
            foreach($inventory_items as $item)
            {
                TempInventories::insert(array(
                    'id' => $item->id,
                    'store_inventory_id' => $item->store_inventory_id,
                    'division' => $item->division,
                    'category' => $item->category,
                    'category_long' => $item->category_long,
                    'sub_category' => $item->sub_category,
                    'brand' => $item->brand,
                    'sku_code' => $item->sku_code,
                    'other_barcode' => $item->other_barcode,
                    'description' => $item->description,
                    'description_long' => $item->description_long,
                    'lpbt' => $item->lpbt,
                    'conversion' => $item->conversion,
                    'ig' => $item->ig,
                    'fso_multiplier' => $item->fso_multiplier,
                    'sapc' => $item->sapc,
                    'whpc' => $item->whpc,
                    'whcs' => $item->whcs,
                    'so' => $item->so,
                    'fso' => $item->fso,
                    'fso_val' => $item->fso_val
                    ));
            }

            $inventory->fixed = 1;
            $inventory->update();
            ItemInventories::where('store_inventory_id',$inventory->id)->delete();

            $store = Store::where('storeid',$inventory->store_id)->first();
            $skus = DB::table('store_items')
                ->select('store_items.id', 'store_items.store_id', 'items.description', 
                    'items.conversion', 'store_items.ig', 'store_items.fso_multiplier', 
                    'items.lpbt', 'categories.category_long','sub_categories.sub_category', 
                    'brands.brand', 'divisions.division', 'other_barcodes.other_barcode', 'items.sku_code')
                ->join('stores', 'stores.id', '=', 'store_items.store_id')
                ->join('items', 'items.id', '=', 'store_items.item_id')
                ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('divisions', 'divisions.id', '=', 'items.division_id')
                ->whereRaw('other_barcodes.area_id = stores.area_id')
                ->where('store_items.store_id', $store->id)
                ->orderBy('items.id', 'asc')
                ->get();

            // dd($skus);

            foreach ($skus as $sku) {
                $item = TempInventories::where('store_inventory_id', $inventory->id)
                    ->where('other_barcode', $sku->other_barcode)
                    ->first();

                // dd($item);

                if(empty($item)){
                    $item2 = Item::with('division')
                        ->with('category')
                        ->with('subcategory')
                        ->with('brand')
                        ->where('sku_code', $sku->sku_code)
                        ->first();

                    ItemInventories::insert([
                        'store_inventory_id' => $inventory->id,
                        'division' => $item2->division->division,
                        'category' => $item2->category->category,
                        'category_long' => $item2->category->category_long,
                        'sub_category' => $item2->subcategory->sub_category,
                        'brand' => $item2->brand->brand,
                        'sku_code' => $item2->sku_code,
                        'other_barcode' => $sku->other_barcode,
                        'description' => $item2->description,
                        'description_long' => $item2->description_long,
                        'lpbt' => $item2->lpbt,
                        'conversion' => $sku->conversion,
                        'ig' => $sku->ig,
                        'fso_multiplier' => $sku->fso_multiplier,
                        'sapc' => 0,
                        'whpc' => 0,
                        'whcs' => 0,
                        'so' => $sku->ig,
                        'fso' => $sku->ig,
                        'fso_val' => $item2->lpbt * $sku->ig]);
                }else{
                    ItemInventories::insert([
                        'store_inventory_id' => $item->store_inventory_id,
                        'division' => $item->division,
                        'category' => $item->category,
                        'category_long' => $item->category_long,
                        'sub_category' => $item->sub_category,
                        'brand' => $item->brand,
                        'sku_code' => $item->sku_code,
                        'other_barcode' => $item->other_barcode,
                        'description' => $item->description,
                        'description_long' => $item->description_long,
                        'lpbt' => $item->lpbt,
                        'conversion' => $item->conversion,
                        'ig' => $item->ig,
                        'fso_multiplier' => $item->fso_multiplier,
                        'sapc' => $item->sapc,
                        'whpc' => $item->whpc,
                        'whcs' => $item->whcs,
                        'so' => $item->ig,
                        'fso' => $item->ig,
                        'fso_val' => $item->fso_val]);
                }
                
            }
        }
    }
}
