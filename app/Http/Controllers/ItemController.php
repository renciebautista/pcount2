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
        $item_type = ItemType::all()->lists('type', 'id');;        
        return view('item.index', compact('items','item_type'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        
        Session::flash('flash_class', 'alert-success');
        Session::flash('flash_message', 'Item successfully deleted.');
        return redirect()->route("item.index");
    }

    public function othercode($id){
        $items = OtherBarcode::where('item_id',$id)->get();
        return view('item.othercode', compact('items'));
    }


    public function updatedig(){
        $items = UpdatedIg::orderBy('updated_at', 'desc')->paginate(100);
            
        return view('item.updatedig',compact('items'));
    }

    public function downloadupdatedig(){
        $items = UpdatedIg::orderBy('updated_at', 'desc')->get();
        $writer = WriterFactory::create(Type::XLSX); 
        $writer->openToBrowser('Store Item Updated IG.xlsx');
        $writer->addRow(array('Area', 'Region', 'Distributor Name', 'Distributor Code', 'Agency', 
            'Store Code', 'Store Id', 'Store Name', 'Channel Name', 'Other Code',
            'SKU Code', 'Description' , 'Division', 'Category', 'Sub Category', 'Brand', 'Conversion', 'Min Stock', 'LPBT', 'IG', 'Date Updated'));  

        foreach ($items as $row) {
            $data[0] = $row->area;
            $data[1] = $row->region;
            $data[2] = $row->distributor;
            $data[3] = $row->distributor_code;
            $data[4] = $row->agency;
            $data[5] = $row->store_code;
            $data[6] = $row->storeid;
            $data[7] = $row->store_name;
            $data[8] = $row->channel;
            $data[9] = $row->other_code;
            $data[10] = $row->sku_code;
            $data[11] = $row->description;
            $data[12] = $row->division;
            $data[13] = $row->category;
            $data[14] = $row->sub_category;
            $data[15] = $row->brand;
            $data[16] = $row->conversion;
            $data[17] = $row->min_stock;
            $data[18] = $row->lpbt;
            $data[19] = $row->ig;
            $data[20] = (string)$row->updated_at;
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
                            if(!empty($row[0])){
                                UpdatedIg::where('store_code',$row[0])
                                    ->where('sku_code',$row[2])
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
}
