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
use App\Models\StoreItem;
use App\Models\UpdatedIg;
use App\Models\OtherBarcode;
use App\DeviceError;
use App\Setting;

class UploadController extends Controller
{
    public function uploadpcount(Request $request)
    {

        $destinationPath = storage_path().'/uploads/pcount/';
        $fileName = $request->file('data')->getClientOriginalName();
        $request->file('data')->move($destinationPath, $fileName);

        $filePath = storage_path().'/uploads/pcount/' . $fileName;

        $filename_data = explode("-", $fileName);

        // dd($filename_data);
        if((count($filename_data) == 6) && ($filename_data[5] == '5.csv')){
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

                $settings = Setting::find(1);
               
                $store_inventory = StoreInventories::where('store_pri_id',$store->id)
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
                    'store_pri_id' => $store->id,
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

                        if(!empty($item)){
                            $osa = 0;
                            $oos = 0;
                            $min_stock = 0;

                            $store_item = StoreItem::where('store_id',$store->id)
                                    ->where('item_id',$item->id)
                                    ->first();

                            if(!isset($row[13])){
                                if(!empty($store_item)){
                                    $min_stock = $store_item->min_stock;
                                }
                            }else{
                                $min_stock = $row[13];
                            }
                            
                            if($row[1] > $min_stock){
                                $osa = 1;
                            }else{
                                $oos = 1;
                            }
                            
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
                                'min_stock' => $min_stock,
                                'ig' => $row[9],
                                'fso_multiplier' => $row[8],
                                'sapc' => $row[1],
                                'whpc' => $row[2],
                                'whcs' => $row[3],
                                'so' => $row[4],
                                'fso' => $row[5],
                                'fso_val' => $row[6],
                                'osa' => $osa,
                                'oos' => $oos,
                                'osa_tagged' => $row[11],
                                'npi_tagged' => $row[12]]);

                            if($settings->enable_ig_edit){
                                if(!empty($store_item)){
                                    if($store_item->ig != $row[9]){

                                        $updated_ig = UpdatedIg::where('store_id',$store->id)
                                            ->where('sku_code',$item->sku_code)
                                            ->first();

                                        $other_code = OtherBarcode::where('item_id', $item->id)
                                                ->where('area_id', $store->area->id)
                                                ->first();
                                        $othercode = '';
                                        if(!empty($other_code)){
                                            $othercode = $other_code->other_barcode;
                                        }
                                            
                                        if(!empty($updated_ig)){
                                            $updated_ig->area = $store->area->area;
                                            $updated_ig->region_code = $store->region->region_code;
                                            $updated_ig->region = $store->region->region;
                                            $updated_ig->distributor_code = $store->distributor->distributor_code;
                                            $updated_ig->distributor = $store->distributor->distributor;
                                            $updated_ig->agency_code = $store->agency->agency_code;
                                            $updated_ig->agency = $store->agency->agency_name;
                                            $updated_ig->storeid = $store->storeid;
                                            $updated_ig->channel_code = $store->channel->channel_code;
                                            $updated_ig->channel = $store->channel->channel_desc;
                                            $updated_ig->other_code = $othercode;
                                            $updated_ig->division = $item->division->division;
                                            $updated_ig->category = $item->category->category; 
                                            $updated_ig->sub_category = $item->subcategory->sub_category; 
                                            $updated_ig->brand = $item->brand->brand; 
                                            $updated_ig->conversion = $item->conversion;
                                            $updated_ig->fso_multiplier = $row[8]; 
                                            $updated_ig->min_stock = $min_stock;
                                            $updated_ig->lpbt = $item->lpbt;
                                            $updated_ig->ig = $row[9];
                                            $updated_ig->updated_at = date('Y-m-d H:i:s');
                                            $updated_ig->save();
                                        }else{
                                            UpdatedIg::create([
                                                'area' => $store->area->area, 
                                                'region_code' => $store->region->region_code,
                                                'region' => $store->region->region,
                                                'distributor_code' => $store->distributor->distributor_code,
                                                'distributor' => $store->distributor->distributor,
                                                'agency_code' => $store->agency->agency_code,
                                                'agency' => $store->agency->agency_name,
                                                'storeid' => $store->storeid,
                                                'store_id' => $store->id,
                                                'store_code' => $store->store_code, 
                                                'store_name' => $store->store_name, 
                                                'channel_code' => $store->channel->channel_code,
                                                'channel' => $store->channel->channel_desc,
                                                'other_code' => $othercode, 
                                                'sku_code' => $item->sku_code, 
                                                'description' => $item->description, 
                                                'division' => $item->division->division, 
                                                'category' => $item->category->category, 
                                                'sub_category' => $item->subcategory->sub_category, 
                                                'brand' => $item->brand->brand, 
                                                'conversion' => $item->conversion,
                                                'fso_multiplier' => $row[8], 
                                                'min_stock' => $min_stock,
                                                'lpbt' => $item->lpbt, 
                                                'ig' => $row[9]]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
               
                $reader->close();

                DB::commit();
               
                return response()->json(array('msg' => 'file uploaded', 'status' => 0));
                
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
            }
        }else{
            return response()->json(array('msg' => 'Cannot upload file, invalid version', 'status' => 1));
        }
        
        
    }

    public function uploadimage(Request $request){
        $destinationPath = storage_path().'/uploads/image/pcount/';
        $fileName = $request->file('data')->getClientOriginalName();
        $request->file('data')->move($destinationPath, $fileName);

        return response()->json(array('msg' => 'file uploaded', 'status' => 0));
    }

    public function uploadtrace(Request $request){
        if ($request->hasFile('data'))
        {
            $destinationPath = storage_path().'/uploads/traces/';
            $filename = $request->file('data')->getClientOriginalName();
            $request->file('data')->move($destinationPath, $filename);

            $error = DeviceError::where('filename',$filename)->first();
            if(!empty($error)){
                $error->updated_at = date('Y-m-d H:i:s');
                $error->update();
            }else{
                DeviceError::create(['filename' => $filename]);
            }

            return response()->json(array('msg' => 'Error trace successfully submitted.', 'status' => 0));
        }
        return response()->json(array('msg' => 'Failed in submitting error trace.', 'status' => 1));
    }

   
}
