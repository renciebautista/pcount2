<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\StoreItem;
use App\Models\OtherBarcode;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
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
        //
    }

    public function othercode($id){
        $items = OtherBarcode::where('item_id',$id)->get();
        return view('item.othercode', compact('items'));
    }


    public function updatedig(){
        $items = StoreItem::where('ig_updated',1)->orderBy('updated_at', 'desc')->get();
        return view('item.updatedig',compact('items'));
    }

    public function downloadupdatedig(){
        $items = StoreItem::where('ig_updated',1)->orderBy('updated_at', 'desc')->get();
        // dd($items);
        $writer = WriterFactory::create(Type::XLSX); 
        $writer->openToBrowser('Store Item Updated IG.xlsx');
        $writer->addRow(array('Store', 'SKU Code', 'Description' , 'Division', 'Category', 'Sub Category', 'Brand', 'Conversion', 'Min Stock', 'LPBT', 'IG', 'Date Updated'));  

        foreach ($items as $row) {
            $data[0] = $row->store->store_name;
            $data[1] = $row->item->sku_code;
            $data[2] = $row->item->description;
            $data[3] = $row->item->division->division;
            $data[4] = $row->item->category->category;
            $data[5] = $row->item->subcategory->sub_category;
            $data[6] = $row->item->brand->brand;
            $data[7] = $row->item->conversion;
            $data[8] = $row->min_stock;
            $data[9] = $row->item->lpbt;
            $data[10] = $row->ig;
            $data[11] = (string)$row->updated_at;
            $writer->addRow($data); 
        }

        $writer->close();
    }
}
