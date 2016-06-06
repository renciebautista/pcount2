<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdatedIg extends Model
{
    protected $fillable = [
        'area',
        'region_code',
        'region',
        'distributor_code',
        'distributor',
        'agency_code',
        'agency',
        'storeid',
        'store_id',
    	'store_code',
    	'store_name',
        'channel_code',
        'channel',
        'other_code',
        'sku_code',
        'description',
        'division',
        'category',
        'sub_category',
        'brand',
        'conversion',
        'min_stock',
        'fso_multiplier',
        'lpbt',
        'ig'
    	];

    public static function search($request){
        return self::where('store_code', 'LIKE', "%$request->search%")
            ->orWhere('store_name', 'LIKE', "%$request->search%")
            ->orWhere('sku_code', 'LIKE', "%$request->search%")
            ->orderBy('updated_at', 'desc')
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }
}
