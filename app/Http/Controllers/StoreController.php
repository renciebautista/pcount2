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
use App\Models\ChannelItem;
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


         $store       = Store::findOrFail($id);
         $area        = Area::orderBy('area','ASC')->lists('area', 'id');
         $enrollment  = Enrollment::orderBy('enrollment','ASC')->lists('enrollment', 'id');
         $distributor = Distributor::orderBy('distributor','ASC')->lists('distributor', 'id');
         $client      = Client::orderBy('client_name','ASC')->lists('client_name', 'id');
         $channel     = channel::orderBY('channel_desc','ASC')->lists('channel_desc', 'id');
         $customer    = Customer::orderBy('customer_name','ASC')->lists('customer_name', 'id');
         $region      = Region::orderBy('region_short','ASC')->lists('region_short','id');
         $agency      = Agency::orderBy('agency_name','ASC')->lists('agency_name','id');
         $status      = ['0' => 'In-active', '1' => 'Active'];
         $user        = StoreUser::where('store_id',$id)->first();
         $alluser     = User::orderBy('username','asc')->lists('username', 'id');

        return view('store.edit',[
            'store'       => $store,
            'area'        => $area ,
            'enrollment'  => $enrollment,
            'distributor' => $distributor,
            'client'      => $client,
            'channel'     => $channel,
            'customer'    => $customer,
            'region'      => $region,
            'agency'      => $agency,
            'status'      => $status,
            'user'        => $user,
            'alluser'     => $alluser ] );
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
            'area_id'        => 'required',
            'enrollment_id'  => 'required',
            'distributor_id' => 'required',
            'client_id'      => 'required',
            'channel_id'     => 'required',
            'customer_id'    => 'required',
            'region_id'      => 'required',
            'agency_id'      => 'required',
            'store_name'     => 'required',
            'store_id'       => 'required',

        ]);

        // $diff_items      = array_diff( $channel_items, $store_items );
        // $same_items      = array_intersect( $channel_items, $store_items );
        // $add_store_items = ChannelItem::select('item_id',
        //                                        'item_type_id',
        //                                        'ig',
        //                                        'fso_multiplier',
        //                                        'min_stock',
        //                                        'ig_updated',
        //                                        'osa_tagged',
        //                                        'npi_tagged' )
        //                                         ->whereIn('item_id',$diff_items)
        //                                         ->where('channel_id',$request->channel_id)
        //                                         ->get();
                                                
       
        // foreach ($add_store_items as &$data) {
        //    $data->store_id = $id; 
        // }
        // $delete      = StoreItem::where('store_id',$id)->whereNotIn('item_id',$same_items)->delete();
        // foreach ($add_store_items as $data) {
        //     $check[] = StoreItem::firstOrCreate([
        //                             'store_id'       => $data->store_id,
        //                             'item_id'        => $data->item_id,
        //                             'item_type_id'   => $data->item_type_id,
        //                             'ig'             => $data->ig,
        //                             'fso_multiplier' => $data->fso_multiplier,
        //                             'min_stock'      => $data->min_stock,
        //                             'ig_updated'     => $data->ig_updated,
        //                             'osa_tagged'     => $data->npi_tagged ]);
        // }




        $store               = Store::findOrFail($id);
        //for mkl
        $mkl_store_items     = StoreItem::where('store_id',$id)
                            ->where('item_type_id',1)
                            ->get()
                            ->pluck('item_id')
                            ->toArray();//get all the item from store mkl

        $mkl_channel_items   = ChannelItem::where('channel_id',$request->channel_id)
                            ->where('item_type_id',1)
                            ->get()
                            ->pluck('item_id')
                            ->toArray();
        //for assortment
        $assortment_store_items = StoreItem::where('store_id',$id)
                                ->where('item_type_id',2)
                                ->get()
                                ->pluck('item_id')
                                ->toArray();//get all the item from store assortment

        $assortment_channel_items   = ChannelItem::where('channel_id',$request->channel_id)
                            ->where('item_type_id',2)
                            ->get()
                            ->pluck('item_id')
                            ->toArray();                    




        //for mkl
        foreach ($mkl_store_items as  $value) {
            if(!in_array($value,$mkl_channel_items)) {
            $delete = StoreItem::where('store_id',$id)
                                ->where('item_type_id',1)
                                ->where('item_id',$value)
                                ->delete();
            }
        }

            $mkl_remaining_items = StoreItem::where('store_id',$id)
                                ->where('item_type_id',1)
                                ->get()
                                ->pluck('item_id')
                                ->toArray();

        foreach ($mkl_channel_items as $value) {

            if(!in_array($value,$mkl_remaining_items)) {

                    $data = ChannelItem::where('item_id',$value)
                                    ->where('channel_id',$request->channel_id)
                                    ->where('item_type_id',1)
                                    ->first();

                    StoreItem::firstOrCreate([
                                'store_id'       => $id,
                                'item_id'        => $data->item_id,
                                'item_type_id'   => $data->item_type_id,
                                'ig'             => $data->ig,
                                'fso_multiplier' => $data->fso_multiplier,
                                'min_stock'      => $data->min_stock,
                                'ig_updated'     => $data->ig_updated,
                                'osa_tagged'     => $data->npi_tagged ]);

            }
        }
        //for assortment
        foreach ($assortment_store_items as  $value) {
            if(!in_array($value,$assortment_channel_items)) {
            $delete = StoreItem::where('store_id',$id)
                                ->where('item_type_id',2)
                                ->where('item_id',$value)
                                ->delete();
            }
        }

        $assortment_remaining_items = StoreItem::where('store_id',$id)
                                    ->where('item_type_id',1)
                                    ->get()
                                    ->pluck('item_id')
                                    ->toArray();


        foreach ($assortment_channel_items as $value) {

            if(!in_array($value,$assortment_remaining_items)) {

                    $data = ChannelItem::where('item_id',$value)
                                    ->where('channel_id',$request->channel_id)
                                    ->where('item_type_id',1)
                                    ->first();

                    $w_mkl = StoreItem::where('store_id',$id)->where('item_id',$value)->get();                
                    if(count($w_mkl) == 0) {
                        StoreItem::firstOrCreate([
                                    'store_id'       => $id,
                                    'item_id'        => $data->item_id,
                                    'item_type_id'   => $data->item_type_id,
                                    'ig'             => $data->ig,
                                    'fso_multiplier' => $data->fso_multiplier,
                                    'min_stock'      => $data->min_stock,
                                    'ig_updated'     => $data->ig_updated,
                                    'osa_tagged'     => $data->npi_tagged ]);
                    }

            }
        }                            

        //end





        $store->area_id          = $request->area_id;
        $store->enrollment_id    = $request->enrollment_id;
        $store->distributor_id   = $request->distributor_id;
        $store->client_id        = $request->client_id;
        $store->channel_id       = $request->channel_id;
        $store->customer_id      = $request->customer_id;
        $store->region_id        = $request->region_id;
        $store->agency_id        = $request->agency_id;
        $store->store_name       = $request->store_name;
        $store->storeid          = $request->store_id;
        $store->store_code       = $request->store_code;
        $store->store_code_psup  = $request->store_code_psup;
        $store->active           = $request->status;
        $store->update();


        \DB::table('store_users')
            ->where('user_id',$request->userid)
            ->where('store_id',$id)
            ->update(['user_id' => $request->user_id]);


        $store       = Store::findOrFail($id);
        $area        = Area::orderBy('area','ASC')->lists('area', 'id');
        $enrollment  = Enrollment::orderBy('enrollment','ASC')->lists('enrollment', 'id');
        $distributor = Distributor::orderBy('distributor','ASC')->lists('distributor', 'id');
        $client      = Client::orderBy('client_name','ASC')->lists('client_name', 'id');
        $channel     = channel::orderBY('channel_desc','ASC')->lists('channel_desc', 'id');
        $customer    = Customer::orderBy('customer_name','ASC')->lists('customer_name', 'id');
        $region      = Region::orderBy('region_short','ASC')->lists('region_short','id');
        $agency      = Agency::orderBy('agency_name','ASC')->lists('agency_name','id');
        $status      = ['0' => 'In-active', '1' => 'Active'];
        $user        = StoreUser::where('store_id',$id)->first();
        $alluser     = User::all()->lists('username', 'id');

        $hash = UpdateHash::find(1);
        if(empty($hash)){
            UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        }else{
            $hash->hash = md5(date('Y-m-d H:i:s'));
            $hash->update();
        }

        Session::flash('flash_class', 'alert-success');
        Session::flash('flash_message', 'Store successfully updated.');

        return view('store.edit',[
            'store'       => $store,
            'area'        => $area ,
            'enrollment'  => $enrollment,
            'distributor' => $distributor,
            'client'      => $client,
            'channel'     => $channel,
            'customer'    => $customer,
            'region'      => $region,
            'agency'      => $agency,
            'status'      => $status,
            'user'        => $user,
            'alluser'     => $alluser ] );

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
