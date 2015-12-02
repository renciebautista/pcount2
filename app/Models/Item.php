<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    public function division()
    {
        return $this->belongsTo('App\Models\Division');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\SubCategory','sub_category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
}
