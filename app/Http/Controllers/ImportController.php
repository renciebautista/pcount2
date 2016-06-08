<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use App\Setting;
use App\Models\UpdateHash;
use App\Jobs\UpdateMasterfile;


class ImportController extends Controller
{
    public function masterfile(){
        return view('import.masterfile');
    }

    public function masterfileuplaod(Request $request){
        // dd($request->all());
        if ($request->hasFile('file'))
		{
            $folderpath = base_path().'/database/seeds/seed_files/'.date('mdY');

            if (!\File::exists($folderpath))
            {
                \File::makeDirectory($folderpath);
            }

            $file_path = $request->file('file')->move($folderpath,'Masterfile.xlsx');

            // $setting = Setting::find(1);
            // $this->dispatch(new UpdateMasterfile($setting));

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
