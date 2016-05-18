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


        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        {!!  Form::close() !!}
    </div>



<!-- <hr> -->

{!! Paginate::show($mappings) !!}
{!! $mappings->render() !!}
<table class="table table-striped table-hover ">
	<thead>
		<tr>
			<th>Premise Code</th>
			<th>Customer Code</th>
			<th>Store Code</th>
			<th>SKU Code</th>
			<th>IG</th>
			<th>Multiplier</th>
			<th>Min Stock</th>
			<th>Type</th>
		</tr>
	</thead>
  <tbody>
		@if(count($mappings) > 0)
		@foreach($mappings as $mapping)
		<tr>
			<td>{{ $mapping->premise_code }}</td>
			<td>{{ $mapping->customer_code }}</td>
			<td>{{ $mapping->store_code }}</td>
			<td>{{ $mapping->sku_code }}</td>
			<td>{{ $mapping->ig }}</td>
			<td>{{ $mapping->multiplier }}</td>
			<td>{{ $mapping->minstock }}</td>
			<td>{{ $mapping->type }}</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="8">No record found.</td>
		</tr>
		@endif
	</tbody>
</table> 

@stop


@section('page-script')



@stop