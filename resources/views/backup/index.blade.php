@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>List of Device with Backup</h3>
        </div>
    </div>
</div>
<hr/>



<label class="pull-right">{{ $backups->count() }} records found.</label>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Username</th>
            <th>Device ID</th>
            <th>Action</th>
      
        </tr>
    </thead>
  <tbody>    
        @if(count($backups) > 0)
            @foreach($backups as $backup)                   
                <tr>
                    <td>{{ $backup->username }}</td>
                    <td>{{ $backup->device_id }}</td>
                    
                     <td>
                        {!! link_to_action('BackupController@show', 'Show Backup', $backup->id, ['class' => 'btn btn-xs btn btn-primary']) !!} 
                    </td>
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