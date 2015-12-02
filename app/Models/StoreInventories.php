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

}
