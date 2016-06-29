<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Models\StoreItem;
use App\Models\InvalidStore;
use Session;
use App\Models\UpdateHash;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        $stores = Store::search($request);
        return view('store.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('store.create');
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
        // if ($request->hasFile('file'))
        // {
        //     $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            
        //     Store::upload($file_path);

        //     if (\File::exists($file_path))
        //     {
        //         \File::delete($file_path);
        //     }

        //     $hash = UpdateHash::find(1);
        //     if(empty($hash)){
        //         UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        //     }else{
        //         $hash->hash = md5(date('Y-m-d H:i:s'));
        //         $hash->update();
        //     }

        //     Session::flash('flash_message', 'Store Masterfile successfully uploaded.');
        //     Session::flash('flash_class', 'alert-success');
        // }else{
        //     Session::flash('flash_message', 'Error uploading masterfile.');
        //     Session::flash('flash_class', 'alert-danger');
            
        // }
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

    public function mkl(Request $request, $id){
        $request->flash();
        $store = Store::findOrFail($id);
        $mkl = StoreItem::search($request,1,$id);
        return view('store.mkl', compact('mkl','store'));
    }

    public function assortment(Request $request, $id){
        $request->flash();
        $store = Store::findOrFail($id);
        $assortment = StoreItem::search($request,2,$id);
        return view('store.assortment', compact('assortment','store'));
    }

    public function invalid(Request $request)
    {
        $request->flash();
        $stores = InvalidStore::search($request);
        return view('store.invalid', compact('stores'));
    }
}
