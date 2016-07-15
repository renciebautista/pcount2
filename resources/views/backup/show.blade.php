@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>List of Backups in Device</h3>
        </div>
    </div>
</div>
<hr/>

 <a href="javascript:history.back()" ><button type="button" class="btn btn-default ">Back </button>
                    </a>
<label class="pull-right">{{ $backups->count() }} records found.</label>
<table class="table table-striped table-hover">
    <thead>
        <tr>
          
            <th>Last Uploaded</th>
        </tr>
    </thead>
  <tbody>  
        @if(count($backups) > 0)
            @foreach($backups as $backup)                   
                <tr>
                    <td>
                        {!! link_to_route('backup.getfile', $backup->updated_at, $backup->filename) !!}
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