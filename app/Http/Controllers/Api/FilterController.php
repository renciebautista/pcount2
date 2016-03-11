<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ItemInventories;
use App\Models\StoreInventories;
use App\Models\AssortmentInventories;
use App\Models\AssortmentItemInventories;

class FilterController extends Controller
{
    public function clientlist(Request $request){
        if(\Request::ajax()){
            $agencies = $request->agencies;
            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }

            if($report_type == 2){
                $data['selection'] = StoreInventories::select('client_code', 'client_name')
                    ->whereIn('store_inventories.agency_code',$agencies)
                    ->groupBy('client_code')
                    ->orderBy('client_name')->lists('client_name', 'client_code');
            }else{
                $data['selection'] = AssortmentInventories::select('client_code', 'client_name')
                    ->whereIn('assortment_inventories.agency_code',$agencies)
                    ->groupBy('client_code')
                    ->orderBy('client_name')->lists('client_name', 'client_code');
            }
            

            return \Response::json($data,200);
        }
    }

    public function channellist(Request $request){
        if(\Request::ajax()){
            $agencies = $request->agencies;
            $clients = $request->clients;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = StoreInventories::select('channel_code', 'channel_name')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->orderBy('channel_name')->lists('channel_name', 'channel_code');
            }else{
                $data['selection'] = AssortmentInventories::select('channel_code', 'channel_name')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->orderBy('channel_name')->lists('channel_name', 'channel_code');
            }
            
            return \Response::json($data,200);
        }
    }

    public function distributorlist(Request $request){
        if(\Request::ajax()){
            $agencies = $request->agencies;
            $clients = $request->clients;
            $channels =$request->channels;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = StoreInventories::select('distributor_code', 'distributor')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->orderBy('distributor')->lists('distributor', 'distributor_code');
            }else{
                $data['selection'] = AssortmentInventories::select('distributor_code', 'distributor')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->orderBy('distributor')->lists('distributor', 'distributor_code');
            }
           

            return \Response::json($data,200);
        }
    }

    public function enrollmentlist(Request $request){
        if(\Request::ajax()){
            $agencies = $request->agencies;
            $clients = $request->clients;
            $channels = $request->channels;
            $distributors = $request->distributors;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = StoreInventories::select('enrollment_type')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->whereIn('distributor_code',$distributors)
                    ->orderBy('enrollment_type')->lists('enrollment_type', 'enrollment_type');
            }else{
                $data['selection'] = AssortmentInventories::select('enrollment_type')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->whereIn('distributor_code',$distributors)
                    ->orderBy('enrollment_type')->lists('enrollment_type', 'enrollment_type');
            }

            

            return \Response::json($data,200);
        }
    }

    public function regionlist(Request $request){
        if(\Request::ajax()){
            $agencies = $request->agencies;
            $clients = $request->clients;
            $channels = $request->channels;
            $distributors = $request->distributors;
            $enrollments = $request->enrollments;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = StoreInventories::select('region_code', 'region_name')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->whereIn('distributor_code',$distributors)
                    ->whereIn('enrollment_type',$enrollments)
                    ->orderBy('region_name')->lists('region_name', 'region_code');
            }else{
                $data['selection'] = AssortmentInventories::select('region_code', 'region_name')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->whereIn('distributor_code',$distributors)
                    ->whereIn('enrollment_type',$enrollments)
                    ->orderBy('region_name')->lists('region_name', 'region_code');
            }
            

            return \Response::json($data,200);
        }
    }

    public function storelist(Request $request){
        if(\Request::ajax()){
            $agencies = $request->agencies;
            $clients = $request->clients;
            $channels = $request->channels;
            $distributors = $request->distributors;
            $enrollments = $request->enrollments;
            $regions = $request->regions;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = StoreInventories::select('store_id', 'store_name')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->whereIn('distributor_code',$distributors)
                    ->whereIn('enrollment_type',$enrollments)
                    ->whereIn('region_code',$regions)
                    ->orderBy('store_name')->lists('store_name', 'store_id');
            }else{
                $data['selection'] = AssortmentInventories::select('store_id', 'store_name')
                    ->whereIn('agency_code',$agencies)
                    ->whereIn('client_code',$clients)
                    ->whereIn('channel_code',$channels)
                    ->whereIn('distributor_code',$distributors)
                    ->whereIn('enrollment_type',$enrollments)
                    ->whereIn('region_code',$regions)
                    ->orderBy('store_name')->lists('store_name', 'store_id');
            }
            

            return \Response::json($data,200);
        }
    }

    public function categorylist(Request $request){
        if(\Request::ajax()){
            $divisions = $request->divisions;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = ItemInventories::select('category')
                    ->whereIn('division',$divisions)
                    ->orderBy('category')->lists('category', 'category');
            }else{
                $data['selection'] = AssortmentItemInventories::select('category')
                    ->whereIn('division',$divisions)
                    ->orderBy('category')->lists('category', 'category');
            }
            

            return \Response::json($data,200);
        }
    }

    public function subcategorylist(Request $request){
        if(\Request::ajax()){
            $divisions = $request->divisions;
            $categories = $request->categories;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = ItemInventories::select('sub_category')
                    ->whereIn('division',$divisions)
                    ->whereIn('category',$categories)
                    ->orderBy('sub_category')->lists('sub_category', 'sub_category');
            }else{
                $data['selection'] = AssortmentItemInventories::select('sub_category')
                    ->whereIn('division',$divisions)
                    ->whereIn('category',$categories)
                    ->orderBy('sub_category')->lists('sub_category', 'sub_category');
            }

            

            return \Response::json($data,200);
        }
    }

    public function brandlist(Request $request){
        if(\Request::ajax()){
            $divisions = $request->divisions;
            $categories = $request->categories;
            $sub_categories = $request->sub_categories;

            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = ItemInventories::select('brand')
                    ->whereIn('division',$divisions)
                    ->whereIn('category',$categories)
                    ->whereIn('sub_category',$sub_categories)
                    ->orderBy('brand')->lists('brand', 'brand');
            }else{
                $data['selection'] = AssortmentItemInventories::select('brand')
                    ->whereIn('division',$divisions)
                    ->whereIn('category',$categories)
                    ->whereIn('sub_category',$sub_categories)
                    ->orderBy('brand')->lists('brand', 'brand');
            }
            

            return \Response::json($data,200);
        }
    }

    

    public function areastorelist(Request $request){
        if(\Request::ajax()){
            $areas = $request->areas;
            $type = $request->type;
            $report_type = 1;
            if((is_null($type)) || ($type != 'assortment')){
                $report_type = 2;
            }
            if($report_type == 2){
                $data['selection'] = StoreInventories::select('store_id', 'store_name')
                    ->whereIn('area',$areas)
                    ->orderBy('store_name')->lists('store_name', 'store_id');
            }else{
                $data['selection'] = AssortmentInventories::select('store_id', 'store_name')
                    ->whereIn('area',$areas)
                    ->orderBy('store_name')->lists('store_name', 'store_id');
            }
            

            return \Response::json($data,200);
        }
    }

}
