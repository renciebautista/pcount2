<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class device_backup extends Model
{
    //
    protected $tables = 'device_backup';

    protected $fillable = [
    	'device_id',
    	'username',
       
    	];
}
