@extends('layout.default')



@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
      	<div class="col-lg-12">
        	<h3>Remove Updated IG</h3>
      	</div>
  	</div>
</div>
<hr/>

{!! Form::open(array('action' => array('ItemController@postremoveig'),  'class' => 'bs-component','files'=>true)) !!}
	<div class="row">
		<div class="col-lg-6">
		  	<div class="form-group">
		    	{!! Form::file('file','',array('id'=>'','class'=>'')) !!}
		  	</div>
		  	{!! Form::submit('Upload', array('class' => 'btn btn-primary')) !!}
		  	{!! link_to_route('item.updatedig','Back',array(),['class' => 'btn btn-default']) !!}
	  	</div>
  	</div>
{!! Form::close() !!}


@stop


