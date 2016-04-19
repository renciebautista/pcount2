<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;



use DB;

use App\Models\StoreInventories;
use App\Models\AssortmentInventories;
use App\Setting;

class DownloadController extends Controller
{
    public function index(Request $request){
        $user = $request->id;
        $type = $request->type;

        $storelist = DB::table('store_users')
                    ->select('stores.id', 'stores.store_code', 'stores.store_name', 'stores.channel_id', 'channels.channel_desc', 'areas.area')
                    ->join('stores', 'stores.id', '=', 'store_users.store_id')
                    ->join('channels', 'channels.id', '=', 'stores.channel_id')
                    ->join('areas', 'areas.id', '=', 'stores.area_id')
                    ->where('store_users.user_id', $user)
                    ->get();

         if($type == 1){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('settings.txt');
            $writer->addRow(array('Enable IG Edit', 'Item Validation'));  

            $settings = Setting::find(1);
            $data[0] = $settings->enable_ig_edit;
            $data[1] = $settings->enable_item_validation;
            $writer->addRow($data); 
            $writer->close();
        }

        // get store list          
        if($type == 2){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('stores.txt');
            $writer->addRow(array('ID', 'Store Code', 'Store Name' , 'Channel Id', 'Channel', 'Area'));  

            foreach ($storelist as $store) {
                $data[0] = $store->id;
                $data[1] = $store->store_code;
                $data[2] = $store->store_name;
                $data[3] = $store->channel_id;
                $data[4] = $store->channel_desc;
                $data[5] = $store->area;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        //get store sku list
        if($type == 3){
            $ids = array();
            foreach ($storelist as $store) {
                $ids[] = $store->id;
            }


            $skus = DB::table('store_items')
                ->select('store_items.id', 'store_items.store_id', 'items.description', 
                    'items.conversion', 'store_items.ig', 'store_items.fso_multiplier', 
                    'items.lpbt', 'categories.category_long','sub_categories.sub_category', 
                    'brands.brand', 'divisions.division', 'other_barcodes.other_barcode', 
                    'items.sku_code', 'items.barcode', 'store_items.min_stock')
                ->join('stores', 'stores.id', '=', 'store_items.store_id')
                ->join('items', 'items.id', '=', 'store_items.item_id')
                ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('divisions', 'divisions.id', '=', 'items.division_id')
                ->where('item_type_id',1)
                ->whereRaw('other_barcodes.area_id = stores.area_id')
                ->whereIn('store_items.store_id', $ids)
                ->orderBy('store_items.id', 'asc')
                ->get();
                
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('mkl.txt');
            $writer->addRow(array('Other Barcode', 'Item Description', 'Inventory Goal', 
                'Conversion', 'LPBT', 'Category', 'Sub-Category', 'Brand', 'Division', 'Store ID', 'Web ID', 'FSO Multiplier', 'Item Barcode', 'Min Stock'));
            
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
                $data[12] = $sku->barcode;
                $data[13] = $sku->min_stock;
                $writer->addRow($data); 
            }

            $writer->close();
        }



        if($type == 4){
            $ids = array();
            foreach ($storelist as $store) {
                $ids[] = $store->id;
            }

            $skus = DB::table('store_items')
                ->select('store_items.id', 'store_items.store_id', 'items.description', 
                    'items.conversion', 'store_items.ig', 'store_items.fso_multiplier', 
                    'items.lpbt', 'categories.category_long','sub_categories.sub_category', 
                    'brands.brand', 'divisions.division', 'other_barcodes.other_barcode', 
                    'items.sku_code', 'items.barcode','store_items.min_stock')
                ->join('stores', 'stores.id', '=', 'store_items.store_id')
                ->join('items', 'items.id', '=', 'store_items.item_id')
                ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('divisions', 'divisions.id', '=', 'items.division_id')
                ->where('store_items.item_type_id',2)
                ->whereRaw('other_barcodes.area_id = stores.area_id')
                ->whereIn('store_items.store_id', $ids)
                ->orderBy('store_items.id', 'asc')
                ->get();
            
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('assortment.txt');
            $writer->addRow(array('Other Barcode', 'Item Description', 'Inventory Goal', 
                'Conversion', 'LPBT', 'Category', 'Sub-Category', 'Brand', 'Division', 'Store ID', 'Web ID', 'FSO Multiplier', 'Item Barcode', 'Min Stock'));
            
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
                $data[12] = $sku->barcode;
                $data[13] = $sku->min_stock;
                $writer->addRow($data); 
            }

            $writer->close();
        }


    }


    public function image($name){
        $file = StoreInventories::where('signature',$name)->first();
        
        $myfile = storage_path().'/uploads/image/pcount/'.$name;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $name);
        }
        
    }

    public function assortmentimage($name){
        $file = AssortmentInventories::where('signature',$name)->first();
        
        $myfile = storage_path().'/uploads/image/assortment/'.$name;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $name);
        }
        
    }

    public function prnlist(){
        $prns = [];
        $filesInFolder = \File::files(base_path().'/storage/prn');

        foreach($filesInFolder as $path)
        {
            $prns[] = pathinfo($path)['basename'];
        }

        if(count($prns)>0){
            return response()->json(array('msg' => 'PRN files lists.', 'files' => $prns));
        }
        
        return response()->json(array('msg' => 'No files found'));

    }

    public function downloadprn($filename){
        
        $myfile = storage_path().'/prn/'.$filename;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $filename);
        }

    }
}
