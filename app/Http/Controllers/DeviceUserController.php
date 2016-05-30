<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
class DeviceUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('log_status',1)->orderby('updated_at')->get();
        return view('device_user.index',['users'=>$users]);
    }

    public function logOut($id)
    {
        $user = User::where('id',$id)->first();                
        $user->log_status = 0;
        $user->device_id = "";
        $user->update();
        return redirect('device_users');
    }


}
