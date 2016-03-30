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



<!-- <hr> -->

<label class="pull-right">{{ $stores->count() }} records found.</label>
<table class="table table-striped table-hover ">
	<thead>
		<tr>
			<th>Area</th>
			<th>Enrollment</th>
			<th>Distributor</th>
			<th>Store ID</th>
			<th>Store Name</th>
			<th>Client</th>
			<th>Channel</th>
			<th>Customer</th>
			<th>Region</th>
			<th>Agency</th>
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