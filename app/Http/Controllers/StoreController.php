<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Models\StoreItem;
use App\Models\InvalidStore;
use App\Models\StoreUser;
use App\Models\Area;
use App\Models\Enrollment;
use App\Models\Distributor;
use App\Models\Client;
use App\Models\Channel;
use App\Models\Customer;
use App\Models\Region;
use App\Models\Agency;
use App\User;
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


         $store= Store::findOrFail($id);
         $area = Area::all()->lists('area', 'id');
         $enrollment = Enrollment::all()->lists('enrollment', 'id');
         $distributor = Distributor::all()->lists('distributor', 'id');
         $client = Client::all()->lists('client_name', 'id');
         $channel = channel::all()->lists('channel_desc', 'id');
         $customer = Customer::all()->lists('customer_name', 'id');
         $region = Region::all()->lists('region_short','id');
         $agency = Agency::all()->lists('agency_name','id');
         $status = ['0' => 'In-active', '1' => 'Active'];

         $user_id = StoreUser::where('store_id',$id)->first();
         $user = User::where('id',$user_id->user_id)->first();
         $alluser= User::all()->lists('username', 'id');
        
        return view('store.edit',['store'=>$store,'area'=>$area ,'enrollment'=>$enrollment,'distributor'=>$distributor,'client'=>$client,'channel'=>$channel,'customer'=>$customer,'region'=>$region,'agency'=>$agency,'status'=>$status,'user'=>$user,'alluser'=>$alluser]);
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
            'area_id' => 'required',
            'enrollment_id' => 'required',
            'distributor_id' => 'required',
            'client_id' => 'required',
            'channel_id' => 'required',
            'customer_id' => 'required',
            'region_id' => 'required',
            'agency_id' => 'required',
            'store_name' => 'required',
            'store_id' => 'required',
            
        ]);
         $store= Store::findOrFail($id);

        $store->area_id = $request->area_id;
        $store->enrollment_id = $request->enrollment_id;
        $store->distributor_id = $request->distributor_id;
        $store->client_id = $request->client_id;
        $store->channel_id = $request->channel_id;
        $store->customer_id = $request->customer_id;
        $store->region_id = $request->region_id;
        $store->agency_id = $request->agency_id;
        $store->store_name = $request->store_name;
        $store->storeid = $request->store_id;
        $store->store_code = $request->store_code;
        $store->store_code_psup = $request->store_code_psup;
        $store->active = $request->status;
        $store->update();

        $storeuser = StoreUser::where('store_id' , $id)->where('user_id',$request->userid)->update(['user_id' => $request->user_id]);



        Session::flash('flash_class', 'alert-success');
        Session::flash('flash_message', 'Item successfully updated.');
        return redirect()->route("store.index");



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
