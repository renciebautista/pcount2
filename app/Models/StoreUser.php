<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreUser extends Model
{
    public  $fillable = ['user_id', 'store_id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User','user_id', 'id');        
    }   
     public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id', 'id');        
    }   
     public function status()
    {
        if($this->active){
            return 'Active';
        }else{
            return 'In-active';
        }
    }
}
