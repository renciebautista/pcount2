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
use App\BackupList;
use App\DeviceBackup;
use App\Setting;

class DownloadController extends Controller
{
    public function index(Request $request){
        $user = $request->id;
        $type = $request->type;
        $ext = $request->ext;

        $storelist = DB::table('store_users')
                    ->select('stores.id', 'stores.store_code', 'stores.store_name', 
                        'stores.channel_id', 'channels.channel_desc', 'areas.area',
                        'enrollments.enrollment',
                        'distributors.distributor_code', 'distributors.distributor',
                        'stores.storeid', 'stores.store_code_psup',
                        'clients.client_code', 'clients.client_name',
                        'channels.channel_code',
                        'customers.customer_code', 'customers.customer_name',
                        'regions.region_code', 'regions.region', 'regions.region_short',
                        'agencies.agency_code', 'agencies.agency_name'
                        )
                    ->join('stores', 'stores.id', '=', 'store_users.store_id')
                    ->join('channels', 'channels.id', '=', 'stores.channel_id')
                    ->join('areas', 'areas.id', '=', 'stores.area_id')
                    ->join('enrollments', 'enrollments.id', '=', 'stores.enrollment_id')
                    ->join('distributors', 'distributors.id', '=', 'stores.distributor_id')
                    ->join('clients', 'clients.id', '=', 'stores.client_id')
                    ->join('customers', 'customers.id', '=', 'stores.customer_id')
                    ->join('regions', 'regions.id', '=', 'stores.region_id')
                    ->join('agencies', 'agencies.id', '=', 'stores.agency_id')
                    ->where('store_users.user_id', $user)
                    ->where('stores.active', 1)
                    ->get();
    

         if($type == 1){
            $settings = Setting::find(1);
            if($ext == 'json'){
                return response()->json($settings);
            }else{
                $writer = WriterFactory::create(Type::CSV); 
                $writer->openToBrowser('settings.txt');
                $data[0] = $settings->enable_ig_edit;
                $data[1] = $settings->validate_posting_mkl;
                $data[2] = $settings->validate_printing_mkl;
                $data[3] = $settings->validate_posting_ass;
                $data[4] = $settings->validate_printing_ass;
                $data[5] = $settings->device_password;
                $writer->addRow($data); 
                $writer->close();
            }
            
        }

        // get store list          
        if($type == 2){
            if($ext == 'json'){
                $json_data = new \stdClass();
                $json_data->total_count = count($storelist);
                foreach ($storelist as $store) {
                    $data = new \stdClass();
                    $data->id = $store->id;
                    $data->store_code = $store->store_code;
                    $data->store_name = $store->store_name;
                    $data->channel_id = $store->channel_id;
                    $data->channel_desc = $store->channel_desc;
                    $data->area = $store->area;
                    $json_data->stores[] = $data;
                }
                return response()->json($json_data);
            }else{
                $writer = WriterFactory::create(Type::CSV); 
                $writer->openToBrowser('stores.txt'); 
                // $writer->addRow(array('ID', 'Store Code', 'Store Name' , 'Channel Id', 'Channel', 'Area'));  
                $writer->addRow(array(count($storelist)));  
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
            
        }

        //get store sku list
        if($type == 3){
            $ids = array();
            foreach ($storelist as $store) {
                $ids[] = $store->id;
            }

            $skus = DB::table('store_items')
                ->select('store_items.id', 'store_items.store_id', 
                    'items.description', 'items.description_long', 
                    'items.conversion', 'store_items.ig', 'store_items.fso_multiplier', 
                    'items.lpbt', 
                     'categories.category', 'categories.category_long',
                    'sub_categories.sub_category', 
                    'brands.brand', 'divisions.division', 'other_barcodes.other_barcode', 
                    'items.sku_code', 'items.barcode', 'store_items.min_stock',
                    'store_items.osa_tagged', 'store_items.npi_tagged')
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

            $updated_igs = DB::table('updated_igs')
                ->whereIn('store_id', $ids)
                ->get();

            $updated_ig_list = [];
            if(!empty($updated_igs)){
                foreach ($updated_igs as $updated_ig) {
                    if(!isset($updated_ig_list[$updated_ig->store_id][$updated_ig->sku_code])){
                        $updated_ig_list[$updated_ig->store_id][$updated_ig->sku_code] = 0;
                    }
                    $updated_ig_list[$updated_ig->store_id][$updated_ig->sku_code] = $updated_ig->ig;
                    
                }
            }
            
            if($ext == 'json'){
                $json_data = new \stdClass();
                $json_data->total_count = count($skus);
                foreach ($skus as $sku) {
                    $data = new \stdClass();
                    $data->conversion = $sku->conversion;
                    $data->lpbt = $sku->lpbt;
                    $data->category_long = $sku->category_long;
                    $data->sub_category = $sku->sub_category;
                    $data->brand = $sku->brand;
                    $data->division = $sku->division;
                    $data->store_id = $sku->store_id;
                    $data->sku_code = $sku->sku_code;
                    $data->fso_multiplier = $sku->fso_multiplier;
                    $data->barcode = $sku->barcode;
                    $data->min_stock = $sku->min_stock;
                    $data->category = $sku->category;
                    $data->description_long = $sku->description_long;
                    $data->osa_tagged = $sku->osa_tagged;
                    $data->npi_tagged = $sku->npi_tagged;
                    $json_data->skus[] = $data;
                }
                return response()->json($json_data);
            }else{
                $writer = WriterFactory::create(Type::CSV); 
                $writer->openToBrowser('mkl.txt');
                // $writer->addRow(array('Other Barcode', 'Item Description', 'Inventory Goal', 
                //     'Conversion', 'LPBT', 'Category Long', 'Sub-Category', 'Brand', 'Division', 'Store ID', 'Web ID', 'FSO Multiplier', 'Item Barcode', 'Min Stock',
                //     'Category', 'Long Desc', 'OSA Tagged', 'NPI Tagged'));
                $writer->addRow(array(count($skus)));  
                foreach ($skus as $sku) {

                    $data[0] = $sku->other_barcode;
                    $data[1] = $sku->description;

                    if(isset($updated_ig_list[$sku->store_id][$sku->sku_code])){
                        $data[2] = $updated_ig_list[$sku->store_id][$sku->sku_code];
                    }else{
                        $data[2] = $sku->ig;
                    }
                   
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
                    $data[14] = $sku->category;
                    $data[15] = $sku->description_long;

                    $data[16] = $sku->osa_tagged;
                    $data[17] = $sku->npi_tagged;
                    
                    $writer->addRow($data); 
                }

                $writer->close();
            }
                
            
        }



        if($type == 4){
            $ids = array();
            foreach ($storelist as $store) {
                $ids[] = $store->id;
            }

            $skus = DB::table('store_items')
                ->select('store_items.id', 'store_items.store_id', 
                    'items.description', 'items.description_long', 
                    'items.conversion', 'store_items.ig', 'store_items.fso_multiplier', 
                    'items.lpbt', 
                     'categories.category', 'categories.category_long',
                    'sub_categories.sub_category', 
                    'brands.brand', 'divisions.division', 'other_barcodes.other_barcode', 
                    'items.sku_code', 'items.barcode', 'store_items.min_stock')
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

            if($ext == 'json'){
                $json_data = new \stdClass();
                $json_data->total_count = count($skus);
                foreach ($skus as $sku) {
                    $data = new \stdClass();
                    $data->conversion = $sku->conversion;
                    $data->lpbt = $sku->lpbt;
                    $data->category_long = $sku->category_long;
                    $data->sub_category = $sku->sub_category;
                    $data->brand = $sku->brand;
                    $data->division = $sku->division;
                    $data->store_id = $sku->store_id;
                    $data->sku_code = $sku->sku_code;
                    $data->fso_multiplier = $sku->fso_multiplier;
                    $data->barcode = $sku->barcode;
                    $data->min_stock = $sku->min_stock;
                    $data->category = $sku->category;
                    $data->description_long = $sku->description_long;
                    $json_data->skus[] = $data;
                }
                return response()->json($json_data);
            }else{

                $writer = WriterFactory::create(Type::CSV); 
                $writer->openToBrowser('assortment.txt');
                // $writer->addRow(array('Other Barcode', 'Item Description', 'Inventory Goal', 
                //     'Conversion', 'LPBT', 'Category', 'Sub-Category', 'Brand', 'Division', 'Store ID', 'Web ID', 'FSO Multiplier', 'Item Barcode', 'Min Stock'));
                $writer->addRow(array(count($skus))); 
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
                    $data[14] = $sku->category;
                    $data[15] = $sku->description_long;
                    $writer->addRow($data); 
                }

                $writer->close();
            }
        }

        if($type == 5){
            $ids = array();
            foreach ($storelist as $store) {
                $ids[] = $store->id;
            }
            $updated_igs = DB::table('updated_igs', 'sku_code', 'ig')
                ->select('store_id','sku_code', 'ig' )
                ->whereIn('store_id', $ids)->get();

            if($ext == 'json'){
                $json_data = new \stdClass();
                $json_data->total_count = count($updated_igs);
                foreach ($updated_igs as $ig) {
                    $data = new \stdClass();
                    $data->store_id = $ig->store_id;
                    $data->sku_code = $ig->sku_code;
                    $data->ig = $ig->ig;
                    $json_data->updated_igs[] = $data;
                }
                return response()->json($json_data);
            }else{

                $writer = WriterFactory::create(Type::CSV); 
                $writer->openToBrowser('updatedig.txt');
                // $writer->addRow(array('Store Id', 'SKU Code', 'IG'));
                $writer->addRow(array(count($updated_igs))); 
                foreach ($updated_igs as $ig) {
                    $data[0] = $ig->store_id;
                    $data[1] = $ig->sku_code;
                    $data[2] = $ig->ig;
                    $writer->addRow($data); 
                }

                $writer->close();
            }

        }

    }


    public function image($name)
    {
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

    public function backuplist($user){
        
        $username=$user;
        $id = DeviceBackup::where('username', $username)->first();
        $filename= BackupList::where('device_backup_id', $id->id)->get();

// foreach ($filename as $value) {
// $fname[]= $value->filename;

// }
 
//         $backups = [];

// foreach ($fname as $f) {
//         $filesInFolder[]  = storage_path().'/uploads/backups/' . $f;
// }

     
        //   foreach($filesInFolder as $path)
        // {
        //     $backups[] = pathinfo($path)['basename'];
        // }

        // foreach ($backups as $b) {
           
        //         $time = backup_list::where('filename',$b)->get();

        // }
   
    
        //     foreach ($time as $t) {
              
        //     }

        // if(count($backups)>0){
        //     return response()->json(array('msg' => 'Backup files lists.', 'files' => $backups));
        // }
  
    if(count($filename) > 0){
        return response()->json(array('msg' =>'Backup files lists.' , 'files'=>$filename) );
    }
    else{
        # code...
         return response()->json(array('msg' => 'No files found'));
    }
    }


    public function downloadbackup($id){

        $file = BackupList::where('id',$id)->first();

        $filename = $file->filename;

        $myfile = storage_path().'/uploads/backups/'.$filename;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
              return \Response::download($myfile, $filename);
        }

    }


}
