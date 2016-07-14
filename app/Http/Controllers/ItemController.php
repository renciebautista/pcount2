<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\StoreItem;
use App\Models\OtherBarcode;
use App\Models\UpdatedIg;
use App\Models\UpdateHash;
use App\Models\Store;
use App\Models\ItemInventories;
use App\Models\Brand;
use App\Models\Division;
use App\Models\Category;
use App\Models\SubCategory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Session;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        $items = Item::search($request);
        return view('item.index', compact('items'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // // dd($request);
        // // if ($request->hasFile('file'))
        // // {
        //     $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            
        //     Item::upload($file_path);

        //     // if (\File::exists($file_path))
        //     // {
        //     //     \File::delete($file_path);
        //     // }

        //     // $hash = UpdateHash::find(1);
        //     // if(empty($hash)){
        //     //     UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        //     // }else{
        //     //     $hash->hash = md5(date('Y-m-d H:i:s'));
        //     //     $hash->update();
        //     // }

        //     Session::flash('flash_message', 'Store Masterfile successfully uploaded.');
        //     Session::flash('flash_class', 'alert-success');
        // // }else{
        // //     Session::flash('flash_message', 'Error uploading masterfile.');
        // //     Session::flash('flash_class', 'alert-danger');
            
        // // }
        // return redirect()->route("store.create");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
$status = ['0' => 'In-active', '1' => 'Active'];
 $divisions = ItemInventories::getDivisionList();
 $brand = Brand::all()->lists('brand', 'id');
            $data = array();
            $sel_dv = [];
             $sel_cat = [];
            $sel_scat = [];
            $sel_br = [];
             if(!empty($sel_cat)){
            $data['categories'] = $sel_cat;
                                                 }
           
            if(!empty($sel_dv)){
            $data['divisions'] = $sel_dv;
                                                  }
            if(!empty($sel_scat)){
            $data['sub_categories'] = $sel_scat;
                                                 }           
            if(!empty($sel_br)){
            $data['brands'] = $sel_br;
                                                 }

        $item= Item::findOrFail($id);
        return view('item.edit',['item' => $item , 'brand'=>$brand],compact('sel_dv','divisions','sel_cat','sel_scat','sel_br','status'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //


        $this->validate($request, [
            'sku_code' => 'required',
            'description' => 'required',
            'conversion' => 'required|numeric',
            'lpbt' => 'required|numeric',
            'division' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'brand_id' => 'required',
            
        ]);
        $item= Item::findOrFail($id);
        
        $divname = $request->division;
        $divid = Division::where('division','=',$divname)->first();
        $divname = $divid->id;
        
        $catname = $request->category;
        $catid = Category::where('category','=',$catname)->first();
        $catname = $catid->id;
        

        $scatname = $request->sub_category;
        $scatid = SubCategory::where('sub_category','=',$scatname)->first();
        $scatname = $scatid->id;
   

        $item->sku_code = $request->sku_code;
        $item->description =$request->description;
        $item->conversion = $request->conversion;
        $item->lpbt =$request->lpbt;
        $item->division_id = $divname;
        $item->category_id =$catname;
        $item->sub_category_id =$scatname;
        $item->brand_id = $request->brand_id;
        $item->description_long =$request->description_long;
        $item->barcode = $request->barcode;
        $item->active = $request->status;
        $item->update();

        Session::flash('flash_class', 'alert-success');
        Session::flash('flash_message', 'Item successfully updated.');
        return redirect()->route("item.index");
  




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        OtherBarcode::where('item_id', $item->id)->delete();
        StoreItem::where('item_id', $item->id)->delete();

        $item->delete();

        $hash = UpdateHash::find(1);
        if(empty($hash)){
            UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        }else{
            $hash->hash = md5(date('Y-m-d H:i:s'));
            $hash->update();
        }
        
        Session::flash('flash_class', 'alert-success');
        Session::flash('flash_message', 'Item successfully deleted.');
        return redirect()->route("item.index");
    }

    public function othercode($id){
        $item = Item::findOrFail($id);
        $items = OtherBarcode::where('item_id',$id)->get();
        return view('item.othercode', compact('item', 'items'));
    }


    public function updatedig(Request $request){
        $request->flash();
        $items = UpdatedIg::search($request);
            
        return view('item.updatedig',compact('items'));
    }

    public function downloadupdatedig(){
        set_time_limit(0);
        ini_set('memory_limit', -1);
        
        $items = UpdatedIg::orderBy('updated_at', 'desc')->get();
        $writer = WriterFactory::create(Type::XLSX); 
        $writer->openToBrowser('Store Item Updated IG.xlsx');
        $writer->addRow(array('Area', 'Region Code', 'Region', 'Distributor Code', 'Distributor Name', 'Agency Code', 'Agency', 
            'Store ID', 'Store Code', 'Store Name', 'Channel Code',  'Channel', 'Other Code',
            'SKU Code', 'Description' , 'Division', 'Category', 'Sub Category', 'Brand', 'Conversion', 'Min Stock', 'FSO Multiplier', 'LPBT', 'IG', 'Created At', 'Date Updated'));  

        foreach ($items as $row) {
            $data[0] = $row->area;
            $data[1] = $row->region_code;
            $data[2] = $row->region;
            $data[3] = $row->distributor_code;
            $data[4] = $row->distributor;
            $data[5] = $row->agency_code;
            $data[6] = $row->agency;
            $data[7] = $row->storeid;
            $data[8] = $row->store_code;
            $data[9] = $row->store_name;
            $data[10] = $row->channel_code;
            $data[11] = $row->channel;
            $data[12] = $row->other_code;
            $data[13] = $row->sku_code;
            $data[14] = $row->description;
            $data[15] = $row->division;
            $data[16] = $row->category;
            $data[17] = $row->sub_category;
            $data[18] = $row->brand;
            $data[19] = $row->conversion;
            $data[20] = $row->min_stock;
            $data[21] = $row->fso_multiplier;
            $data[22] = $row->lpbt;
            $data[23] = $row->ig;
            $data[24] = (string)$row->created_at;
            $data[25] = (string)$row->updated_at;
            $writer->addRow($data); 
        }

        $writer->close();
    }

    public function removeig(){
        return view('item.removeig');
    }

    public function postremoveig(Request $request){
        if ($request->hasFile('file'))
        {
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            
            \DB::beginTransaction();
            try {
                $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
                $reader->open($file_path);

                foreach ($reader->getSheetIterator() as $sheet) {
                    if($sheet->getName() == 'Sheet1'){
                        $cnt = 0;
                        foreach ($sheet->getRowIterator() as $row) {
                            if(!empty($row[5])){
                                UpdatedIg::where('store_code',$row[8])
                                    ->where('sku_code',$row[13])
                                    ->delete();
                            }
                            
                        }
                    }
                    
                }
                 \DB::commit();
                $reader->close();
            } catch (\Exception $e) {
                dd($e);
                \DB::rollback();
            }

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }

            Session::flash('flash_message', 'Updated IG successfully updated.');
            Session::flash('flash_class', 'alert-success');
        }else{
            Session::flash('flash_message', 'Error updating item IG.');
            Session::flash('flash_class', 'alert-danger');
            
        }
        return redirect()->route("item.updatedig");
    }

    public function updateig(){
        return view('item.updateig');
    }

    public function postupdateig(Request $request){
        if ($request->hasFile('file'))
        {
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            
            \DB::beginTransaction();
            try {
                set_time_limit(0);
                $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
                $reader->open($file_path);

                foreach ($reader->getSheetIterator() as $sheet) {
                    if($sheet->getName() == 'Sheet1'){
                        $cnt = 0;
                        foreach ($sheet->getRowIterator() as $row) {
                            if($cnt > 0){
                                if(!empty($row[5])){
                                    // dd($row);
                                    $updated_ig = UpdatedIg::where('store_code',$row[8])
                                        ->where('sku_code',$row[13])->first();
                                    if(!empty($updated_ig)){
                                        $updated_ig->ig = $row[23];
                                        $updated_ig->update();
                                    }else{
                                        // dd($row);
                                        UpdatedIg::firstOrCreate([
                                            'area' => $row[0],
                                            'region_code' => $row[1],
                                            'region' => $row[2],
                                            'distributor_code' => $row[3],
                                            'distributor' => $row[4],
                                            'agency_code' => $row[5],
                                            'agency' => $row[6],
                                            'storeid' => $row[7],
                                            'store_code' => $row[8],
                                            'store_name' => $row[9],
                                            'channel_code' => $row[10],
                                            'channel' => $row[11],
                                            'other_code' => $row[12],
                                            'sku_code' => $row[13],
                                            'description' => $row[14],
                                            'division' => $row[15],
                                            'category' => $row[16],
                                            'sub_category' => $row[17],
                                            'brand' => $row[18],
                                            'conversion' => $row[19],
                                            'min_stock' => $row[20],
                                            'fso_multiplier' => $row[21],
                                            'lpbt' => $row[22],
                                            'ig' => $row[23],
                                        ]);
                                    }

                                    $store = Store::where('store_code',$row[8])->first();
                                    if(!empty($store)){
                                        $item = Item::where('sku_code', $row[13])->first();
                                        if(!empty($item)){
                                            StoreItem::where('store_id', $store->id)
                                                ->where('item_id', $item->id)
                                                ->update(['ig' => $row[23]]);
                                        }
                                    }
     
                                }
                            }
                            $cnt++;
                        }
                    }
                    
                }
                $hash = UpdateHash::find(1);
                if(empty($hash)){
                    UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
                }else{
                    $hash->hash = md5(date('Y-m-d H:i:s'));
                    $hash->update();
                }
                 \DB::commit();

                $reader->close();
            } catch (\Exception $e) {
                \DB::rollback();
                 dd($e);
            }

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }

            Session::flash('flash_message', 'Updated IG successfully updated.');
            Session::flash('flash_class', 'alert-success');
        }else{
            Session::flash('flash_message', 'Error updating item IG.');
            Session::flash('flash_class', 'alert-danger');
            
        }
        return redirect()->route("item.updatedig");
    }

}
