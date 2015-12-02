<?php

namespace App\Http\Controllers\Api;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\User;
use App\Models\Store;
use App\Models\StoreInventories;
use App\Models\ItemInventories;
use App\Models\Item;
use App\Models\UpdateHash;

class UploadController extends Controller
{
    public function uploadpcount(Request $request)
    {
        $destinationPath = storage_path().'/uploads/pcount/';
        $fileName = $request->file('data')->getClientOriginalName();
        $request->file('data')->move($destinationPath, $fileName);

        // $fileName = "30-4-11-16-2015.csv";
        // $filePath = storage_path().'/uploads/pcount/' . $fileName;

        $filename_data = explode("-", $fileName);
        $storeid = $filename_data[0];
        $userid = $filename_data[1];
        $year = explode(".", $filename_data[4]);
        $transdate = date('Y-m-d', strtotime($year[0] . '-' . $filename_data[2] . '-' . $filename_data[3]));

        $imgname = explode(".", $fileName);
        $signature = 'IM_' . $imgname[0] . '.jpg';

        $store = Store::with('area')
            ->with('enrollment')
            ->with('distributor')
            ->with('client')
            ->with('channel')
            ->with('customer')
            ->with('region')
            ->with('agency')
            ->find($storeid);
        $user = User::find($userid);

        DB::beginTransaction();
        try {
            // dd($store);
            $store_inventory = StoreInventories::where('store_id',$store->storeid)
                ->where('transaction_date', $transdate)->first();
            if(!empty($store_inventory)){
                ItemInventories::where('store_inventory_id', $store_inventory->id)->delete();
                $store_inventory->delete();
            }


            $store_inventory = StoreInventories::create([
                'area' => $store->area->area,
                'enrollment_type' => $store->enrollment->enrollment,
                'distributor_code' => $store->distributor->distributor_code,
                'distributor' => $store->distributor->distributor,
                'store_id' => $store->storeid,
                'store_code' => $store->store_code,
                'store_code_psup' => $store->store_code_psup,
                'store_name' => $store->store_name,
                'client_code' => $store->client->client_code,
                'client_name' => $store->client->client_name,
                'channel_code' => $store->channel->channel_code,
                'channel_name' => $store->channel->channel_desc,
                'customer_code' => $store->customer->customer_code,
                'customer_name' => $store->customer->customer_name,
                'region_short_name' => $store->region->region_short,
                'region_name' => $store->region->region,
                'region_code' => $store->region->region_code,
                'agency_code' => $store->agency->agency_code,
                'agency' => $store->agency->agency_name,
                'username' => $user->name,
                'signature' => $signature,
                'transaction_date' => $transdate
                ]);
            
            $reader = ReaderFactory::create(Type::CSV); // for XLSX files
            $reader->setFieldDelimiter(';');
            $reader->open($filePath);

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $item = Item::with('division')
                        ->with('category')
                        ->with('subcategory')
                        ->with('brand')
                        ->where('sku_code', trim($row[0]))
                        ->first();

                    ItemInventories::insert([
                        'store_inventory_id' => $store_inventory->id,
                        'division' => $item->division->division,
                        'category' => $item->category->category,
                        'category_long' => $item->category->category_long,
                        'sub_category' => $item->subcategory->sub_category,
                        'brand' => $item->brand->brand,
                        'sku_code' => $item->sku_code,
                        'other_barcode' => $row[7],
                        'description' => $item->description,
                        'description_long' => $item->description_long,
                        'lpbt' => $item->lpbt,
                        'conversion' => $row[10],
                        'ig' => $row[9],
                        'fso_multiplier' => $row[8],
                        'sapc' => $row[1],
                        'whpc' => $row[2],
                        'whcs' => $row[3],
                        'so' => $row[4],
                        'fso' => $row[5],
                        'fso_val' => $row[6]]);
                }
            }
           
            $reader->close();

            $hash = UpdateHash::find(1);
            if(empty($hash)){
                UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
            }else{
                $hash->hash = \Hash::make(date('Y-m-d H:i:s'));
                $hash->update();
            }
            


            DB::commit();
           
            return response()->json(array('msg' => 'file uploaded', 'status' => 0));
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
        }
        
    }

    public function uploadimage(Request $request){
        $destinationPath = storage_path().'/uploads/image/';
        $fileName = $request->file('data')->getClientOriginalName();
        $request->file('data')->move($destinationPath, $fileName);

        return response()->json(array('msg' => 'file uploaded', 'status' => 0));
    }
}
