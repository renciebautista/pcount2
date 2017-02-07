@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
	<div class="row">
		<div class="col-lg-12">
			<h3>Channel List</h3>
		</div>
	</div>
</div>
<hr/>

<div class="box box-default">
		{!! Form::open(array('method' => 'get','class' => 'bs-component')) !!}


        <div class="form-group">
         	<label>Search Channel Name</label>
          	{!! Form::text('search',null,['class' => 'form-control', 'placeholder' => 'Keywords']) !!}
        </div>
      

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        {!!  Form::close() !!}
    </div>



<!-- <hr> -->

{!! Paginate::show($channels) !!}
{!! $channels->render() !!}
<table class="table table-striped table-hover ">
	<thead>
		<tr>
			<th>Channel Description</th>
			
			<th>Action</th>
			

			
		</tr>
	</thead>
  <tbody>
		@if(count($channels) > 0)
		@foreach($channels as $channel)
		<tr>
			<td>{{ $channel->channel_desc }}</td>
			


			
			

			<td>
				{!! link_to_action('ChannelController@mkl', 'MKL', $channel->channel_id, ['class' => 'btn btn-xs btn btn-primary']) !!}
			
				{!! link_to_action('ChannelController@assortment', 'ASSORTMENT', $channel->channel_id, ['class' => 'btn btn-xs btn btn-primary']) !!}
			</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="2">No record found.</td>
		</tr>
		@endif
	</tbody>
</table> 

@stop


@section('page-script')



@stop