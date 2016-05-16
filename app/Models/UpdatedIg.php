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
        'fso_multiplier',
        'min_stock',
        'lpbt',
        'ig'
    	];
}
