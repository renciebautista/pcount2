<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    
   
    public function storeitem()
    {
        return $this->hasMany(StoreItem::class);
    }
}
