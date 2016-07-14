<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class backup_list extends Model
{
    //

    protected $table= 'backup_lists';
    protected $fillable = [
    	'filename',
    	'device_backup_id',
        
    	];

    	public function backup_device()
    {
        return $this->belongsTo('App\backup_device');
    }
}
