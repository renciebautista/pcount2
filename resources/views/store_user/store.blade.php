@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>User Stores List</h3>
        </div>
    </div>
</div>
<hr/>
<a href="javascript:history.back()" ><button type="button" class="btn btn-default ">Back </button></a>
<label class="pull-right">{{ $stores->count() }} records found.</label>
<table class="table table-striped table-hover">
    <thead>
        <tr>
        	<th>Store Code</th>   
            <th>Store</th>                    
        </tr>
    </thead>
    <tbody>    
        @if(count($stores) > 0)
            @foreach($stores as $store)                   
                <tr>
                	<td>{{ $store->store->store_code }}</td>                  
                    <td>{{ $store->store->store_name }}</td>                  
                </tr>                
            @endforeach
        @else
        <tr>
            <td colspan="9">No record found.</td>
        </tr>
        @endif    
    </tbody>
</table> 

@stop
@section('page-script')
@stop