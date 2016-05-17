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

class UploadController extends Controller
{
    public function uploadpcount(Request $request)
    {

        $destinationPath = storage_path().'/uploads/pcount/';
        $fileName = $request->file('data')->getClientOriginalName();
        $request->file('data')->move($destinationPath, $fileName);

        $filePath = storage_path().'/uploads/pcount/' . $fileName;

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

                    $store_item = StoreItem::where('store_id',$store->id)
                        ->where('item_id',$item->id)
                        ->first();

                    $osa = 0;
                    $oos = 0;
                    $total_stockcs = $row[1]+$row[2]+ ($row[3] * $row[10]);
                    $min_stock = 0;
                    if(!empty($store_item)){
                        $min_stock = $store_item->min_stock;
                    }
                    

                    // stocks > min_stock = osa

                    if($total_stockcs > $min_stock){
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
                        'ig' => $row[9],
                        'fso_multiplier' => $row[8],
                        'sapc' => $row[1],
                        'whpc' => $row[2],
                        'whcs' => $row[3],
                        'so' => $row[4],
                        'fso' => $row[5],
                        'fso_val' => $row[6],
                        'osa' => $osa,
                        'oos' => $oos]);

                    
                    // dd($store_item);
                    if(!empty($store_item)){
                        if($store_item->ig != $row[9]){
                            $store_item->ig = $row[9];
                            $store_item->ig_updated = 1;
                            $store_item->update();

                            $updated_ig = UpdatedIg::where('store_code',$store->store_code)
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
                                $updated_ig->min_stock = $store_item->min_stock;
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
                                    'min_stock' => $store_item->min_stock,
                                    'lpbt' => $item->lpbt, 
                                    'ig' => $row[9]]);
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
        
    }

    public function uploadimage(Request $request){
        $destinationPath = storage_path().'/uploads/image/pcount/';
        $fileName = $request->file('data')->getClientOriginalName();
        $request->file('data')->move($destinationPath, $fileName);

        return response()->json(array('msg' => 'file uploaded', 'status' => 0));
    }
}
