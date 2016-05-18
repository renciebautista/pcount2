<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class InvalidMapping extends Model
{
    public static function search($request){
        return self::where('premise_code', 'LIKE', "%$request->search%")
        	->orWhere('customer_code', 'LIKE', "%$request->search%")
        	->orWhere('store_code', 'LIKE', "%$request->search%")
        	->orWhere('sku_code', 'LIKE', "%$request->search%")
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }
}
