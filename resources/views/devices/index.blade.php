@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Device List</h3>
        </div>
    </div>
</div>
<hr/>



<!-- <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid"> -->
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Device ID</th>
            <th>Version</th>
            <th>Last Logged In</th>
        </tr>
    </thead>
  <tbody>    
        @if(count($devices) > 0)
            @foreach($devices as $device)                   
                <tr>
                    <td>{{ $device->device_id }}</td>
                    <td>{{ $device->version }}</td>
                    <td>{{ $device->updated_at }}</td>
                </tr>                
            @endforeach
        @else
        <tr>
            <td colspan="9">No record found.</td>
        </tr>
        @endif    
    </tbody>
</table> 
</div>

@stop


@section('page-script')

$('#item_type').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control',
    });

    


@stop