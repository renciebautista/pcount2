@extends('layout.default')



@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
      	<div class="col-lg-12">
        	<h3>App Settings</h3>
      	</div>
  	</div>
</div>
<hr/>

{!! Form::open(array('action' => array('SettingsController@store'),  'class' => 'bs-component', 'role' => 'role')) !!}
	<div class="row">
		<div class="col-lg-6">

        <div class="form-group">
                  <label>Uploader Email</label>
                  {!! Form::text('uploader_email',$settings->uploader_email,['class' => 'form-control']) !!}
                </div>

		  	<div class="form-group">
		    	<div class="checkbox">
                  <label>
                  	{!! Form::checkbox('enable_ig_edit', '1', $settings->enable_ig_edit) !!} Allow to edit Inventory Goal (IG)
                  </label>
                </div>
		  	</div>

		  	<div class="form-group">
		    	<div class="checkbox">
                  <label>
                  	{!! Form::checkbox('validate_posting_mkl', '1', $settings->validate_posting_mkl) !!} Validate SO / OSA item before posting.
                  </label>
                </div>
		  	</div>

            <div class="form-group">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('validate_printing_mkl', '1', $settings->validate_printing_mkl) !!} Validate SO / OSA item before printing.
                  </label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('validate_posting_ass', '1', $settings->validate_posting_ass) !!} Validate Assortment item before posting.
                  </label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('validate_printing_ass', '1', $settings->validate_printing_ass) !!} Validate Assortment item before printing.
                  </label>
                </div>
            </div>
		  	{!! Form::submit('Update', array('class' => 'btn btn-primary')) !!}
	  	</div>
  	</div>
{!! Form::close() !!}


@stop


