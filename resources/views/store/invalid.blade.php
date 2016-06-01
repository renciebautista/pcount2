@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
	<div class="row">
		<div class="col-lg-12">
			<h3>Invalid Store List</h3>
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
			<th>Distributor</th>
			<th>Store ID</th>
			<th>Store Code</th>
			<th>Store Name</th>
			<th>Username</th>
			<th>Status</th>
			<th>Remarks</th>
		</tr>
	</thead>
  <tbody>
		@if(count($stores) > 0)
		@foreach($stores as $store)
		<tr>
			<td>{{ $store->area }}</td>
			<td>{{ $store->distributor }}</td>
			<td>{{ $store->storeid }}</td>
			<td>{{ $store->store_code }}</td>
			<td>{{ $store->store_name }}</td>
			<td>{{ $store->username }}</td>
			<td>{{ $store->status }}</td>
			<td>{{ $store->remarks }}</td>
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