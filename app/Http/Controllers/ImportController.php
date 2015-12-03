<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

use App\Models\UpdateHash;


class ImportController extends Controller
{
    public function masterfile(){
        return view('import.masterfile');
    }

    public function masterfileuplaod(Request $request){
        if ($request->hasFile('file'))
		{
		    $file_path = $request->file('file')->move(storage_path().'/uploads/',$request->file('file')->getClientOriginalName());

		    \Artisan::call('db:seed');

		    $hash = UpdateHash::find(1);
            if(empty($hash)){
                UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
            }else{
                $hash->hash = md5(date('Y-m-d H:i:s'));
                $hash->update();
            }

		   	Session::flash('flash_message', 'Masterfile successfully added.');
			Session::flash('flash_class', 'alert-success');
		}else{
			Session::flash('flash_message', 'Error updating masterfile.');
			Session::flash('flash_class', 'alert-danger');
        	
		}
		return redirect()->route("import.masterfile");
    }
}
