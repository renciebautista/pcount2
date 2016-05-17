<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
    	'device_id',
    	'username',
        'version',
    	];
}
