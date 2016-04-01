<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Models\StoreItem;
class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::all();
        return view('store.index', compact('stores'));
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

    public function items($id){
        $skus = StoreItem::with('item')->where('store_id',$id)->get();
        return view('store.items', compact('skus'));
    }

    public function mkl($id){
        $mkl = StoreItem::join('stores', 'stores.id', '=', 'store_items.store_id')
            ->join('items', 'items.id', '=', 'store_items.item_id')
            ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->where('item_type_id',1)
            ->whereRaw('other_barcodes.area_id = stores.area_id')
            ->where('store_items.store_id', $id)
            ->orderBy('items.id', 'asc')
            ->get();
        return view('store.mkl', compact('mkl'));
    }

    public function assortment($id){

        $assortment = StoreItem::join('stores', 'stores.id', '=', 'store_items.store_id')
            ->join('items', 'items.id', '=', 'store_items.item_id')
            ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->where('item_type_id',2)
            ->whereRaw('other_barcodes.area_id = stores.area_id')
            ->where('store_items.store_id', $id)
            ->orderBy('items.id', 'asc')
            ->get();
        return view('store.assortment', compact('assortment'));
    }
}
