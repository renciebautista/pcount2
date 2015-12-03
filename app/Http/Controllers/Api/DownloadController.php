<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

use App\User;
use App\Store;
use App\StoreSku;
use App\Inventory;


use DB;

class DownloadController extends Controller
{
    public function index(Request $request){
        $user = $request->id;
        $type = $request->type;

        $storelist = DB::table('store_users')
                    ->select('stores.id', 'stores.store_code', 'stores.store_name', 'stores.channel_id')
                    ->join('stores', 'stores.id', '=', 'store_users.store_id')
                    ->where('store_users.user_id', $user)
                    ->get();

        // get store list          
        if($type == 1){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('stores.txt');
            $writer->addRow(array('ID', 'Store Code', 'Store Name'));  

            foreach ($storelist as $store) {
                $data[0] = $store->id;
                $data[1] = $store->store_code;
                $data[2] = $store->store_name;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        //get store sku list
        if($type == 2){
            $ids = array();
            foreach ($storelist as $store) {
                $ids[] = $store->id;
            }


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
                ->whereIn('store_items.store_id', $ids)
                ->orderBy('items.id', 'asc')
                ->get();
            
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('skus.txt');
            $writer->addRow(array('Other Barcode', 'Item Description', 'Inventory Goal', 
                'Conversion', 'LPBT', 'Category', 'Sub-Category', 'Brand', 'Division', 'Store ID', 'Web ID', 'FSO Multiplier'));
            
            foreach ($skus as $sku) {
                $data[0] = $sku->other_barcode;
                $data[1] = $sku->description;
                $data[2] = $sku->ig;
                $data[3] = $sku->conversion;
                $data[4] = $sku->lpbt;
                $data[5] = $sku->category_long;
                $data[6] = $sku->sub_category;
                $data[7] = $sku->brand;
                $data[8] = $sku->division;
                $data[9] = $sku->store_id;
                $data[10] = $sku->sku_code;
                $data[11] = $sku->fso_multiplier;
                $writer->addRow($data); 
            }

            $writer->close();
        }
    }


    public function image($name){
        $file = Inventory::where('signature',$name)->first();
        
        $myfile = storage_path().'/uploads/image/'.$name;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $name);
        }

        
    }
}
