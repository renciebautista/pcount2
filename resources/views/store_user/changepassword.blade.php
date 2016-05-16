@extends('layout.default')



@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
      	<div class="col-lg-12">
        	<h3>Change User Password</h3>
      	</div>
  	</div>
</div>
<hr/>

{!! Form::open(array('route' => array('store_user.postupdate', $user->id),  'class' => 'bs-component', 'method' => 'PUT')) !!}
	<div class="row">
		<div class="col-lg-6">

        <div class="form-group">
          	<label>Full Name</label>
          	{!! Form::text('name',$user->name,['class' => 'form-control' , 'readonly' => 'readonly']) !!}
        </div>

        <div class="form-group">
          	<label>User Name</label>
          	{!! Form::text('username',$user->username,['class' => 'form-control' , 'readonly' => 'readonly']) !!}
        </div>

        <div class="form-group">
          	<label>Email</label>
          	{!! Form::text('email',$user->email,['class' => 'form-control' , 'readonly' => 'readonly']) !!}
        </div>

        <div class="form-group">
          	<label>Password</label>
          	{!! Form::password('password',['class' => 'form-control']) !!}
        </div>

         <div class="form-group">
          	<label>Confirm Password</label>
          	{!! Form::password('password_confirmation',['class' => 'form-control']) !!}
        </div>
		  	
		  	{!! Form::submit('Update', array('class' => 'btn btn-primary')) !!}
		  	{!! link_to_route('store_user.edit', 'Back',$user, array('class' => 'btn btn-default')) !!}
	  	</div>
  	</div>
{!! Form::close() !!}


@stop


