<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Setting;
use Session;
use App\Models\UpdateHash;


class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::find(1);
        return view('settings.index',compact('settings'));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $settings = Setting::find(1);
        $settings->uploader_email = $request->uploader_email;
        $settings->enable_ig_edit = ($request->has('enable_ig_edit')) ? 1 : 0;
        $settings->validate_posting_mkl = ($request->has('validate_posting_mkl')) ? 1 : 0;
        $settings->validate_printing_mkl = ($request->has('validate_printing_mkl')) ? 1 : 0;
        $settings->validate_posting_ass = ($request->has('validate_posting_ass')) ? 1 : 0;
        $settings->validate_printing_ass = ($request->has('validate_printing_ass')) ? 1 : 0;
        $settings->device_password = $request->device_password;
        $settings->update();

        $hash = UpdateHash::find(1);
        if(empty($hash)){
            UpdateHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
        }else{
            $hash->hash = md5(date('Y-m-d H:i:s'));
            $hash->update();
        }

        Session::flash('flash_message', 'Settings successfully updated.');
        Session::flash('flash_class', 'alert-success');

        return redirect()->route("settings.index");
    }

    
}
