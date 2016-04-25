<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdatedIg extends Model
{
    protected $fillable = [
    	'store_code',
        'sku_code',
        'min_stock',
        'ig'
    	];
}
