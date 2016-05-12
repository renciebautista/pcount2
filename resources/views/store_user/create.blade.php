@extends('layout.default')



@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
      	<div class="col-lg-12">
        	<h3>Add User</h3>
      	</div>
  	</div>
</div>
<hr/>

{!! Form::open(array('route' => array('store_user.store'),  'class' => 'bs-component')) !!}
	<div class="row">
		<div class="col-lg-6">

        <div class="form-group">
          	<label>Full Name</label>
          	{!! Form::text('name',null,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          	<label>User Name</label>
          	{!! Form::text('username',null,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          	<label>Email</label>
          	{!! Form::text('email',null,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          	<label>Password</label>
          	{!! Form::password('password',['class' => 'form-control']) !!}
        </div>

         <div class="form-group">
          	<label>Confirm Password</label>
          	{!! Form::password('password_confirmation',['class' => 'form-control']) !!}
        </div>

         <div class="form-group">
          	<label>Role Group</label>

          	{!! Form::select('role',$roles,null, array('class' => 'form-control', 'placeholder' => 'Please Select')) !!}
        </div>
		  	
		  	{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
		  	{!! link_to_route('store_user.index', 'Back',null, array('class' => 'btn btn-default')) !!}
	  	</div>
  	</div>
{!! Form::close() !!}


@stop


