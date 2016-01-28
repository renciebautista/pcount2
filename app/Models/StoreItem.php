<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    public $timestamps = false;

    public function item(){
    	return $this->belongsTo('App\Models\Item');
    }
}
