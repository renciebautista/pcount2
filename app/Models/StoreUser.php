<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreUser extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User','user_id', 'id');        
    }   
     public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id', 'id');        
    }   
}
