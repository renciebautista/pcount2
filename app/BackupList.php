<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackupList extends Model
{
    //

    protected $table= 'backup_lists';
    protected $fillable = [
    	'filename',
    	'device_backup_id',
        'database_version'
        
    	];

    	public function backup_device()
    {
        return $this->belongsTo('App\backup_device');
    }
}
