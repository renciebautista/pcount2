<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\StoreUser;
use App\Models\RoleUser;
use App\User;
use App\Role;
use Session;

class StoreUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();

       
        $users = User::search($request);
        // $roles = Role::where('id', '>', 2)->first();

        // if(!empty($roles)){
        //     $users = $roles->users()->get();
        // }

       
        $roles = Role::orderBy('name')->lists('name', 'id');


        return view('store_user.index',compact('users','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->lists('name', 'id');
        return view('store_user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'same:password',
            'role' => 'required|integer|min:1'
        ]);

        $role = Role::findOrFail($request->role);

        $user = new User();
        $user->name = strtoupper($request->name);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();



        $user->roles()->attach($role);

        Session::flash('flash_message', 'User successfully added.');
        Session::flash('flash_class', 'alert-success');

        return redirect()->route("store_user.index");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->lists('name', 'id');
        // dd($roles);
        return view('store_user.edit',compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|integer|min:1'
        ]);

        $role = Role::findOrFail($request->role);
        
        $user->name = strtoupper($request->name);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->update();

        $user->detachRoles($user->roles);

        $user->roles()->attach($role);

        Session::flash('flash_message', 'User successfully updated.');
        Session::flash('flash_class', 'alert-success');

        return redirect()->route("store_user.index");
    }

     public function changestatus(Request $request)
    {   


        $stats = $request->get('id'); 
       $value = $request->get('active');
 
   $user = User::findOrFail($stats);
   $user->active = $request->get('active') ;
   $user->update();
           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function storelist($id){
        $stores = StoreUser::where('user_id',$id)->get();
        return view('store_user.store', compact('stores'));
    }

    public function changepassword($id){
        $user = User::findOrFail($id);
        return view('store_user.changepassword',compact('user'));
    }

    public function postupdate(Request $request, $id){

        $user = User::findOrFail($id);
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'same:password'
        ]);

        $user->password = \Hash::make($request->password);
        $user->update();


        Session::flash('flash_message', 'User password successfully updated.');
        Session::flash('flash_class', 'alert-success');

        return redirect()->route("store_user.edit", $user);
    }
}
