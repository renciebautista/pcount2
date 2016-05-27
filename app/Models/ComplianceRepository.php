<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ComplianceRepository extends Model
{
    public static function getAllArea(){
    	$mkl = DB::table('store_inventories')->select('area')->distinct('area');
    	
    	return DB::table('assortment_inventories')->select('area')->distinct('area')
    		->union($mkl)
    		->orderBy('area')
    		->lists('area', 'area');

    }

    public static function getAllAgency(){
        $mkl = DB::table('store_inventories')->select('agency_code', 'agency');
        
        return DB::table('assortment_inventories')->select('agency_code', 'agency')
            ->union($mkl)
            ->orderBy('agency')
            ->groupBy('agency_code')
            ->lists('agency', 'agency_code');

    }

    public static function getAllRegion(){
        $mkl = DB::table('store_inventories')->select('region_code', 'region_name');
        
        return DB::table('assortment_inventories')->select('region_code', 'region_name')
            ->union($mkl)
            ->orderBy('region_name')
            ->groupBy('region_code')
            ->lists('region_name', 'region_code');
    }

    public static function getAllChannel(){
        $mkl = DB::table('store_inventories')->select('channel_code', 'channel_name');
        
        return DB::table('assortment_inventories')->select('channel_code', 'channel_name')
            ->union($mkl)
            ->orderBy('channel_name')
            ->groupBy('channel_code')
            ->lists('channel_name', 'channel_code');
    }

    public static function getAllStore(){
        $mkl = DB::table('store_inventories')->select('store_code', 'store_name');
        
        return DB::table('assortment_inventories')->select('store_code', 'store_name')
            ->union($mkl)
            ->orderBy('store_name')
            ->groupBy('store_code')
            ->lists('store_name', 'store_code');
    }

    public static function getAllUser(){
        $mkl = DB::table('store_inventories')->select('username');
        
        return DB::table('assortment_inventories')->select('username')
            ->union($mkl)
            ->orderBy('username')
            ->groupBy('username')
            ->lists('username', 'username');
    }

    public static function allareastorelist($areas){
    	
    	$mkl = DB::table('store_inventories')->select('store_id', 'store_name')->whereIn('area',$areas);
    	return DB::table('assortment_inventories')->select('store_id', 'store_name')
    		->union($mkl)
    		->whereIn('area',$areas)
    		->orderBy('store_name')->lists('store_name', 'store_id');
    }

    public static function getAssortmentCompliance($filters){
        $from = '';
        if(!empty($filters['from'])){
            $date = explode("-", $filters['from']);
            $from = "where transaction_date >= '" . $date[2].'-'.$date[0].'-'.$date[1]."'";
        }
        $to = '';
        if(!empty($filters['to'])){
            $date = explode("-", $filters['to']);
            $to = " and transaction_date <= '" . $date[2].'-'.$date[0].'-'.$date[1]."'";
        }
        $areas = '';
        if(!empty($filters['areas'])){
            $areas = " and area in ('" . implode(",", $filters['areas']). "')";
        }
        $stores = '';
        if(!empty($filters['stores'])){
            $areas = " and store_id in ('" . implode(",", $filters['stores']). "')";
        }
        
       
        $query = sprintf("select *, count(
                        case
                            when osa = 1
                            then 1
                            else null
                        end
                    ) as passed,
                    count(
                        case
                            when osa = 0
                            then 0
                            else null
                        end
                    ) as failed,
                    count(*) as total
        from (
        select area, region_name, distributor , distributor_code, agency, store_id, store_code, store_name, client_name, osa, transaction_date,
        SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week
        from item_inventories
        join `store_inventories` on store_inventories.id = item_inventories.store_inventory_id
        union all
        select area, region_name, distributor , distributor_code, agency, store_id, store_code, store_name, client_name, osa, transaction_date,
        SUBSTRING(yearweek(transaction_date,3),1,4) as yr,week(transaction_date,3) as yr_week
        from assortment_item_inventories
        join `assortment_inventories` on assortment_inventories.id = assortment_item_inventories.store_inventory_id
        ) as tbl
        %s
        %s
        %s
        %s
        group by yr, store_name,yr_week, area
        order by yr, store_name,yr_week, area",$from,$to,$areas,$stores);
        return \DB::select(\DB::raw($query));
    }
}
