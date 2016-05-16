@extends('layout.default')



@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
      	<div class="col-lg-12">
        	<h3>Edit User</h3>
      	</div>
  	</div>
</div>
<hr/>

{!! Form::open(array('route' => array('store_user.update', $user->id),  'class' => 'bs-component', 'method' => 'PUT')) !!}
	<div class="row">
		<div class="col-lg-6">

        <div class="form-group">
          	<label>Full Name</label>
          	{!! Form::text('name',$user->name,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          	<label>User Name</label>
          	{!! Form::text('username',$user->username,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          	<label>Email</label>
          	{!! Form::text('email',$user->email,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          	<label>Role Group</label>

          	{!! Form::select('role',$roles,$user->roles[0]->id, array('class' => 'form-control', 'placeholder' => 'Please Select')) !!}
        </div>
		  	
		  	{!! Form::submit('Update', array('class' => 'btn btn-primary')) !!}
        {!! link_to_route('store_user.changepassword', 'Change Password',$user->id, array('class' => 'btn btn-primary')) !!}
		  	{!! link_to_route('store_user.index', 'Back',null, array('class' => 'btn btn-default')) !!}
	  	</div>
  	</div>
{!! Form::close() !!}


@stop


