<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

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

		   Session::flash('flash_message', 'Masterfile successfully added.');
			Session::flash('flash_class', 'alert-success');
		}else{
			Session::flash('flash_message', 'Error updating masterfile.');
			Session::flash('flash_class', 'alert-danger');
        	
		}
		return redirect()->route("import.masterfile");
    }
}
