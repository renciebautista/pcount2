<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreInventories extends Model
{
    protected $fillable = [
    	'area',
        'enrollment_type',
        'distributor_code',
        'distributor',
        'store_id',
        'store_pri_id',
        'store_code',
        'store_code_psup',
        'store_name',
        'client_code',
        'client_name',
        'channel_code',
        'channel_name',
        'customer_code',
        'customer_name',
        'region_short_name',
        'region_name',
        'region_code',
        'agency_code',
        'agency',
        'username',
        'signature',
        'transaction_date'
    	];

    public static function getAreaList(){
        return self::select('area')
            ->groupBy('area')
            ->lists('area', 'area');
    }

    public static function getAgencyList(){
        return self::select('agency_code', 'agency')
            ->groupBy('agency_code')
            ->lists('agency', 'agency_code');
    }


    public static function getStoreCodes($value){
        $list = array();
        $records = self::select($value)
            ->groupBy($value)
            ->get();
        foreach ($records as $row) {
            $list[] = (string)$row->$value;
        }
        return $list;
    }

}
