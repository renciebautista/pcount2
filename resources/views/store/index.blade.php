@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
	<div class="row">
		<div class="col-lg-12">
			<h3>Store List</h3>
		</div>
	</div>
</div>
<hr/>

<div class="box box-default">
		{!! Form::open(array('method' => 'get','class' => 'bs-component')) !!}


        <div class="form-group">
         	<label>Search Store Name</label>
          	{!! Form::text('search',null,['class' => 'form-control', 'placeholder' => 'Keywords']) !!}
        </div>
        <div class="form-group">
			<label class="radio-inline">
				{!! Form::radio('status', 1, true) !!} Active
			</label>
			<label class="radio-inline">
				{!!  Form::radio('status', 2) !!} In-active			
			</label>
			<label class="radio-inline">
			  	{!! Form::radio('status', 3) !!} All	
			</label>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        {!!  Form::close() !!}
    </div>



<!-- <hr> -->

{!! Paginate::show($stores) !!}
{!! $stores->render() !!}
<table class="table table-striped table-hover ">
	<thead>
		<tr>
			<th>Area</th>
			<th>Enrollment</th>
			<th>Distributor</th>
			<th >Store ID</th>
			<th>Store Name</th>
			<th>Client</th>
			<th>Channel</th>
			<th>Customer</th>
			<th>Region</th>
			<th>Agency</th>
			<th>Status</th>
			<th>Action</th>
			

			<th colspan="2"></th>
		</tr>
	</thead>
  <tbody>
		@if(count($stores) > 0)
		@foreach($stores as $store)
		<tr>
			<td>{{ $store->area->area }}</td>
			<td>{{ $store->enrollment->enrollment }}</td>
			<td>{{ $store->distributor->distributor }}</td>
			<td>{{ $store->storeid }}</td>
			<td>{{ $store->store_name }}</td>
			<td>{{ $store->client->client_name }}</td>
			<td>{{ $store->channel->channel_desc }}</td>
			<td>{{ $store->customer->customer_name }}</td>
			<td>{{ $store->region->region_short }}</td>
			<td>{{ $store->agency->agency_name }}</td>
			<td>{{ $store->status() }}</td>


			<td>{!! link_to_action('StoreController@edit', 'Edit', $store->id, ['class' => 'btn btn-xs btn btn-primary']) !!}</td>

			

			<td>
				{!! link_to_action('StoreController@mkl', 'MKL', $store->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
			</td>
			<td>
				{!! link_to_action('StoreController@assortment', 'ASSORTMENT', $store->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
			</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="11">No record found.</td>
		</tr>
		@endif
	</tbody>
</table> 

@stop


@section('page-script')



@stop