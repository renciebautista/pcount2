@extends('layout.default')



@section('content')
@include('shared.notifications')


<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Upload Test Apk</h3>
        </div>
    </div>
</div>
<hr/>

<div class="row">

	<div class="col-md-12">
		{!! Form::open(array('action' => array('TestApkController@store'),  'class' => 'bs-component','files'=>true)) !!}
	<div class="row">
		<div class="col-lg-6">
		  	<div class="form-group">
		    	{!! Form::file('file','',array('id'=>'','class'=>'')) !!}
		  	</div>
		  	{!! Form::submit('Upload', array('class' => 'btn btn-primary btn-sm')) !!}
		  	{!! Html::linkAction('TestApkController@index', 'Back', array(), array('class' => 'btn btn-default btn-sm')) !!}
	  	</div>
  	</div>
{!! Form::close() !!}
	</div>
</div>
@endsection