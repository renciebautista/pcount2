@extends('layout.default')



@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
      	<div class="col-lg-12">
        	<h3>Add Role Group</h3>
      	</div>
  	</div>
</div>
<hr/>

{!! Form::open(array('route' => array('roles.store'),  'class' => 'bs-component')) !!}
	<div class="row">
		<div class="col-lg-6">

        <div class="form-group">
          	<label>Role Name</label>
          	{!! Form::text('name',null,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>Display Name</label>
            {!! Form::text('display_name',null,['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label>Description</label>
            {!! Form::text('description',null,['class' => 'form-control']) !!}
        </div>

		  	
		  	{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
		  	{!! link_to_route('roles.index', 'Back',null, array('class' => 'btn btn-default')) !!}
	  	</div>
  	</div>
{!! Form::close() !!}


@stop


