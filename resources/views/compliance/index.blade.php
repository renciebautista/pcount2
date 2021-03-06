@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Total Compliance Report</h3>
        </div>
    </div>
</div>
<hr/>

{!! Form::open(array('route' => array('compliance.store'), 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'form_filtered')) !!}

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
        <div class="form-group">
                {!! Form::label('store', 'Store', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="stores" name="st[]" multiple="multiple" ></select>                
            </div>
        </div>
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
<label class="pull-right">{{count($compliances)  }} records found.</label>
<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Area</th>
            <th>Store Name</th>
            <th class="right">Year</th>
            <th class="right">Week #</th>
            <th class="right">With Stock</th>
            <th class="right">OOS</th>
            <th class="right">Compliance</th>
        </tr>
    </thead>
  <tbody>
        @if(count($compliances) > 0)
        @foreach($compliances as $item)
        <tr>
            <td>{{ $item->client_name }}</td>
            <td>{{ $item->area }}</td>
            <td>{{ $item->store_name }}</td>
            <td class="right">{{ $item->yr }}</td>
            <td class="right">{{ $item->yr_week }}</td>
            <td class="right">{{ $item->passed }}</td>
            <td class="right">{{ $item->failed }}</td>
            @if(strtoupper($item->client_name) == 'MT MDC')
            <td class="right">{{ number_format(($item->passed/$item->total) * 100,2 )}}%</td>
            @else
            <td class="right">{{ number_format(($item->failed/$item->total)* 100,2 )}}%</td>
            @endif
           
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

 var st = <?php echo json_encode($sel_st); ?>;

$('#ar').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control',
        onChange: function(option, checked, select) {
            updatestore(); 
        }
    });

    $('#stores').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control'
    });

    function updatestore(){
        $.ajax({
            type: "POST",
            data: {areas: GetSelectValues($('select#ar :selected'))},
            url: "{{ route('allareastorelist')}}",
            success: function(data){
                $('select#stores').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,st ) > -1){
                      sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#stores')); 
                });
                $('select#stores').multiselect('rebuild');
           }
        });
    }

    var divag = $("select#ar").val();
    if(divag !== null) {
        updatestore();
    }

@stop