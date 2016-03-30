@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>{{ $header }}</h3>
        </div>
    </div>
</div>
<hr/>

{!! Form::open(array('route' => array('osa.postarea', $type), 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'form_filtered')) !!}

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
                {!! Form::label('area', 'Area', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('ar[]', $areas, $sel_ar, array('class' => 'form-control select_form', 'id' => 'ar', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">

    </div>

    <div class="col-lg-4">

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
<label class="pull-right">{{count($inventories)  }} records found.</label>
<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>Area</th>
            <th class="right">Year</th>
            <th class="right">Week #</th>
            <th class="right">Without Stock</th>
            <th class="right">With Stock</th>
            <th></th>
        </tr>
    </thead>
  <tbody>
        @if(count($inventories) > 0)
        @foreach($inventories as $item)
        <tr>
            <td>{{ $item->area }}</td>
            <td class="right">{{ $item->yr }}</td>
            <td class="right">{{ $item->yr_week }}</td>
            <td class="right">{{ $item->failed }}</td>
            <td class="right">{{ $item->passed }}</td>
            <td></td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7">No record found.</td>
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

$('#ar').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control',
    });

@stop