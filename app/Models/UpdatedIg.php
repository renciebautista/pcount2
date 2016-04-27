<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdatedIg extends Model
{
    protected $fillable = [
    	'store_code',
    	'store_name',
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
