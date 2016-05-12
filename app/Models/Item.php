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

    public function storeitem()
    {
        return $this->belongsTo('App\Models\StoreItem','id', 'item_id');
    }
    
    public static function search($request){
        // return self::with(['area' => function ($query) {
        //         $query->orderBy('area', 'desc');
        //     }])
            return self::where('description', 'LIKE', "%$request->search%")
            ->orWhere('sku_code', 'LIKE', "%$request->search%")
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }



}
