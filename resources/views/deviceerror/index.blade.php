@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Device Error Report</h3>
        </div>
    </div>
</div>
<hr/>

<label class="pull-right">{{ $devices->count() }} records found.</label>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Device Id</th>
            <th>Last Reported</th>
        </tr>
    </thead>
  <tbody>  

        @if(count($devices) > 0)
            @foreach($devices as $device)                   
                <tr>
                    <td>
                        {!! link_to_route('deviceerror.getfile', $device->filename, $device->filename) !!}
                    </td>
                    <td>{{ $device->updated_at}}</td>
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