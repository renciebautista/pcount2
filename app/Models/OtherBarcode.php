<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherBarcode extends Model
{
    public $timestamps = false;

    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }
}
