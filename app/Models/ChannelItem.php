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



     public static function search($request){
        return self::select('channels.channel_desc', 'channel_items.channel_id','channels.channel_code')
            ->where(function($query) use ($request){
            if(!empty($request->search)){
                $query->where('channels.channel_code', 'LIKE', "%$request->search%");
                $query->orWhere('channels.channel_desc', 'LIKE', "%$request->search%");
            }
            })
            ->join('channels', 'channels.id', '=', 'channel_items.channel_id')
            ->groupBy('channels.id')
            ->paginate(100)

            ->appends(['search' => $request->search]);
    }



      public static function search_items($request, $type, $id){
        return self::join('channels', 'channels.id', '=', 'channel_items.channel_id')
            ->join('items', 'items.id', '=', 'channel_items.item_id')
            ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->where('item_type_id',$type)
          
            ->where('channel_items.channel_id', $id)
            ->where(function($query) use ($request){
                $query->where('items.sku_code', 'LIKE', "%$request->search%");
                $query->orWhere('items.description', 'LIKE', "%$request->search%");
            })
            ->groupBy('channel_items.id')
            ->orderBy('channel_items.id', 'asc')
            ->get();
    }

}
