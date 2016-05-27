@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Posting History Report</h3>
        </div>
    </div>
</div>
<hr/>

{!! Form::open(array('route' => array('history.postposting'), 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'form_filtered')) !!}

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('fr', 'Date From', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::text('fr',$frm,array('class' => 'form-control', 'id' => 'from', 'data-date-format' => 'mm-dd-yyyy', 'placeholder' => 'mm-dd-yyyy')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('to', 'Date To', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::text('to',$to,array('class' => 'form-control', 'id' => 'to', 'data-date-format' => 'mm-dd-yyyy', 'placeholder' => 'mm-dd-yyyy')) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('agency', 'Agency', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('ag[]', $agencies, $sel_ag, array('class' => 'form-control select_form', 'id' => 'ag', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('region', 'Region', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('rg[]', $regions, $sel_rg, array('class' => 'form-control select_form', 'id' => 'rg', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('channel', 'Channel', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('ch[]', $channels, $sel_ch, array('class' => 'form-control select_form', 'id' => 'ch', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('store', 'Store', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('st[]', $stores, $sel_st, array('class' => 'form-control select_form', 'id' => 'st', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('username', 'Username', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('us[]', $users, $sel_us, array('class' => 'form-control select_form', 'id' => 'us', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('type', 'Posting Type', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('ty[]', $types, $sel_ty, array('class' => 'form-control select_form', 'id' => 'ty', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4">
        <div class="form-group">
            <div class="row">
                <div class="col-lg-1">
                </div>
                <div class="col-lg-11">
                    <button type="submit" name="submit" value="submit" class="btn btn-info" ><span class="glyphicon glyphicon-search"></span> PROCESS</button>
                    <button type="submit" name="download" value="download" class="btn btn-success" ><span class="glyphicon glyphicon-download-alt"></span> DOWNLOAD EXCEL</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
    </div>

    <div class="col-lg-4">
        
    </div>
</div>
{!! Form::close() !!}


<!-- <hr> -->
<label class="pull-right">{{count($postings)  }} records found.</label>
<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>Agency</th>
            <th>Region</th>
            <th>Channel</th>
            <th>Store Name</th>
            <th>Store Code</th>
            <th>Username</th>
            <th>Type</th>
            <th>Transaction Date</th>
            <th >Posting Date</th>
        </tr>
    </thead>
  <tbody>
        @if(count($postings) > 0)
        @foreach($postings as $post)
        <tr>
            <td>{{ $post->agency }}</td>
            <td>{{ $post->region_name }}</td>
            <td>{{ $post->channel_name }}</td>
            <td>{{ $post->store_name }}</td>
            <td>{{ $post->store_code }}</td>
            <td>{{ $post->username }}</td>
            <td>{{ $post->type }}</td>
            <td>{{ $post->transaction_date }}</td>
            <td>{{ $post->updated_at }}</td>
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

$("#from").datepicker({
        dateFormat: "mm-dd-yy",
        showWeek: true,
        firstDay: 1,
        onSelect: function(selected) {
            $("#to").datepicker("option","minDate", selected)
        }
    });


$("#to").datepicker({
    dateFormat: "mm-dd-yy",
    showWeek: true,
    firstDay: 1,
    onSelect: function(selected) {
        $("#from").datepicker("option","maxDate", selected)
    }
});

$('#ag, #rg, #ch, #st, #us, #ty').multiselect({
    maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
    buttonClass: 'form-control',
});


@stop