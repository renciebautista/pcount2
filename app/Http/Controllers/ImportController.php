<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use App\Setting;
use App\Models\UpdateHash;
use App\Jobs\UpdateMasterfile;

use Excel;

use DB;

use App\Models\Store;
use App\User;
use App\Models\StoreUser;


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
            }
            else
            {
                $hash->hash = md5(date('Y-m-d H:i:s'));
                $hash->update();
            }
		   	Session::flash('flash_message', 'Masterfile successfully added.');
			Session::flash('flash_class', 'alert-success');
		    }
            else
            {
			Session::flash('flash_message', 'Error updating masterfile.');
			Session::flash('flash_class', 'alert-danger');
        	}
		      return redirect()->route("import.masterfile");
   

 }



    public function remapping(){

        return view('import.remapping');

    }

    public function remappinguplaod(Request $request){

          if ($request->hasFile('file'))
          {

            $destinationPath = storage_path().'/uploads/remapping/';
            $fileName = $request->file('file')->getClientOriginalName();
            $request->file('file')->move($destinationPath, $fileName);
            $filePath = storage_path().'/uploads/remapping/' . $fileName;

            $data = Excel::load($filePath ,function($reader){})->get();
            if(!empty($data) && $data->count())
            {
                foreach ($data as $key => $value) 
                {
                    $old = $value->old;
                    $new = $value->new;
                    $storecode = $value->storecode;
                # code...
                if(!empty($old) && !empty($new) && !empty($storecode)){  

                        $old_id = User::where('username',$old)->first();
                        $new_id = User::where('username', $new)->first();
                        $store_id = Store::where('store_code' , $storecode)->first(); 

                        if(!empty($old_id) && !empty($new_id) && !empty($store_id)){


                        \DB::table('store_users')   
                        ->where('user_id',$old_id->id)
                        ->where('store_id',$store_id->id)
                        ->update(['user_id' => $new_id->id]);

                        Session::flash('flash_message', 'Store User successfully update.');
                        Session::flash('flash_class', 'alert-success');
                        }
                        else{
                     Session::flash('flash_message', 'Error updating remapping.');
                        Session::flash('flash_class', 'alert-danger');

                        }

                }
                else{

                        Session::flash('flash_message', 'Error updating remapping.');
                        Session::flash('flash_class', 'alert-danger');
                }

                }
             }

            }
              else
            {
                        Session::flash('flash_message', 'Error updating remapping.');
                        Session::flash('flash_class', 'alert-danger');
            }
                        return redirect()->route("import.remapping");
        }   





}
