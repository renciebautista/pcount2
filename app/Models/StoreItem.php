<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    // public $timestamps = false;
     protected $fillable = ['store_id', 'item_id', 'item_type_id','ig','fso_multiplier','min_stock','ig_updated','osa_tagged','npi_tagged','created_at','updated_at'];

    public function item(){
    	return $this->belongsTo('App\Models\Item');
    }

    public function store(){
    	return $this->belongsTo('App\Models\Store');
    }

    public function itemtype(){
    	return $this->belongsTo('App\Models\ItemType','item_type_id','id');    	
    }    

    public static function search($request, $type, $id){
        return self::join('stores', 'stores.id', '=', 'store_items.store_id')
            ->join('items', 'items.id', '=', 'store_items.item_id')
            ->join('other_barcodes', 'other_barcodes.item_id', '=', 'items.id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'items.sub_category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('divisions', 'divisions.id', '=', 'items.division_id')
            ->where('item_type_id',$type)
            ->whereRaw('other_barcodes.area_id = stores.area_id')
            ->where('store_items.store_id', $id)
            ->where(function($query) use ($request){
                $query->where('items.sku_code', 'LIKE', "%$request->search%");
                $query->orWhere('items.description', 'LIKE', "%$request->search%");
            })
            ->orderBy('store_items.id', 'asc')
            ->get();
    }

   public static function getPartial($take,$skip,$type){
        return self::select(\DB::raw('store_code, store_name, sku_code, barcode, description, ig,fso_multiplier, min_stock'))
            ->join('stores', 'stores.id', '=', 'store_items.store_id')
            ->join('items', 'items.id', '=', 'store_items.item_id')
            ->where('item_type_id', $type)
            ->skip($skip*$take)
            ->take($take)
            ->get();
   }

  
}
