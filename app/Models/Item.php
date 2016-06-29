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

    public function status()
    {
        if($this->active){
            return 'Active';
        }else{
            return 'In-active';
        }
    }
    
    public static function search($request){
            return self::where(function($query) use ($request){
                $query->where('description', 'LIKE', "%$request->search%");
                $query->orWhere('sku_code', 'LIKE', "%$request->search%");
            })
            ->where(function($query) use ($request){
            if(!empty($request->status)){
                    if($request->status == 1){
                        $query->where('active', 1);
                    }

                    if($request->status == 2){
                        $query->where('active',0);
                    }
                    
                }else{
                    $query->where('active', 1);
                }
            })
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }



}
