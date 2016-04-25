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
        $items = UpdatedIg::join('stores', 'stores.store_code', '=', 'updated_igs.store_code')
            ->join('items', 'items.sku_code', '=', 'updated_igs.sku_code')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->get();
        return view('item.updatedig',compact('items'));
    }

    public function downloadupdatedig(){
        $items =  UpdatedIg::join('stores', 'stores.store_code', '=', 'updated_igs.store_code')
            ->join('items', 'items.sku_code', '=', 'updated_igs.sku_code')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->orderBy('updated_igs.updated_at', 'desc')
            ->get();
        // $items = StoreItem::where('ig_updated',1)->orderBy('updated_at', 'desc')->get();
        // dd($items);
        $writer = WriterFactory::create(Type::XLSX); 
        $writer->openToBrowser('Store Item Updated IG.xlsx');
        $writer->addRow(array('Store Code', 'Store', 'SKU Code', 'Description' , 'Division', 'Category', 'Sub Category', 'Brand', 'Conversion', 'Min Stock', 'LPBT', 'IG', 'Item Type', 'Date Updated'));  

        foreach ($items as $row) {
            $data[0] = $row->store_code;
            $data[1] = $row->store_name;
            $data[2] = $row->sku_code;
            $data[3] = $row->description;
            $data[4] = $row->division;
            $data[5] = $row->category;
            $data[6] = $row->sub_category;
            $data[7] = $row->brand;
            $data[8] = $row->conversion;
            $data[9] = $row->min_stock;
            $data[10] = $row->lpbt;
            $data[11] = $row->ig;
            $data[12] = (string)$row->updated_at;
            $writer->addRow($data); 
        }

        $writer->close();
    }
}
