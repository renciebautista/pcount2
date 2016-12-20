<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelItem extends Model
{
    //

    public function item(){
      return $this->belongsTo('App\Models\Item');
    }

    public function channel(){
      return $this->belongsTo('App\Models\Channel');
    }

    public function itemtype(){
      return $this->belongsTo('App\Models\ItemType','item_type_id','id');
    }
}
