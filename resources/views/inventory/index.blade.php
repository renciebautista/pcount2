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
{!! Form::open(array('route' => array('inventory.index', $type), 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'form_filtered')) !!}

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
                {!! Form::label('ag', 'Agency', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('ag[]', $agencies, $sel_ag, array('class' => 'form-control select_form', 'id' => 'agencies', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('cl', 'Client', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="clients" name="cl[]" multiple="multiple" ></select>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('ch', 'Channel', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="channels" name="ch[]" multiple="multiple" ></select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('ds', 'Distributor', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="distributors" name="ds[]" multiple="multiple" ></select>              
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('en', 'Enrollment', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="enrollments" name="en[]" multiple="multiple" ></select>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('rg', 'Region', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="regions" name="rg[]" multiple="multiple" ></select>               
            </div>
        </div>
    </div>
</div>


<div class="row">

    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('st', 'Store', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="stores" name="st[]" multiple="multiple" ></select>                
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
                {!! Form::label('dv', 'Division', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('dv[]', $divisions, $sel_dv, array('class' => 'form-control select_form', 'id' => 'divisions', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('ct', 'Category', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="categories" name="ct[]" multiple="multiple" ></select>                
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('sc', 'Sub Category', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="sub_categories" name="sc[]" multiple="multiple" ></select>                
            </div>
        </div>
    </div>
    
</div>


<div class="row">
	<div class="col-lg-4">
         <div class="form-group">
                {!! Form::label('br', 'Brand', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="brands" name="br[]" multiple="multiple" ></select>                
            </div>
        </div>
	</div>
	

	<div class="col-lg-4">
         <div class="form-group">
                {!! Form::label('availability', 'Availability', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('availability[]', $availability, $sel_av, array('class' => 'form-control select_form', 'id' => 'av', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    @if($header=='MKL Posted Transaction Report')
    <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('tag', 'Item Tag', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('tags[]', $tags, $sel_tag, array('class' => 'form-control select_form', 'id' => 'tag', 'multiple' => 'multiple')) !!}
            </div>
        </div>
    </div>
    @else
    <div class="col-lg-4">
    </div>
    @endif
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

{!! Paginate::show($items) !!}
<!-- <hr> -->
<table class="table table-striped table-hover ">
  	<thead>
		<tr>
	  		<th>Store Code</th>
	  		<th>Store Name</th>
	  		<th>Other Code</th>
	 	 	<th>SKU Code</th>
	  		<th>Item Description</th>
            <th>IG</th>
	  		<th>SAPC</th>
	  		<th>WHPC</th>
	  		<th>WHCS</th>
	  		<th>SO</th>
	  		<th>FSO</th>
            <th>OSA</th>
            <th>OOS</th>
            <th>OSA Tagged</th>
            <th>NPI Tagged</th>
	  		<th>Transanction Date</th>
		</tr>
  	</thead>
  <tbody>
  		@if(count($items) > 0)
  		@foreach($items as $item)
		<tr>
	  	    <td>{{ $item->store_code }}</td>
	  		<td>{{ $item->store_name }}</td>
			<td>{{ $item->other_barcode }}</td>
			<td>{{ $item->sku_code }}</td>
			<td>{{ $item->description }}</td>
            <td class="right">{{ $item->ig }}</td>
			<td class="right">{{ number_format($item->sapc) }}</td>
			<td class="right">{{ number_format($item->whpc) }}</td>
			<td class="right">{{ number_format($item->whcs) }}</td>
			<td class="right">{{ number_format($item->so) }}</td>
			<td class="right">{{ number_format($item->fso) }}</td>
            <td class="right">{{ $item->osa }}</td>
            <td class="right">{{ $item->oos }}</td>

            <td class="right">{{ $item->osa_tagged }}</td>
            <td class="right">{{ $item->npi_tagged }}</td>

			<td class="right">{{ $item->transaction_date }}</td>
		</tr>
		@endforeach
		@else
		<tr>
	  		<td colspan="16">No record found.</td>
		</tr>
		@endif
  	</tbody>
</table> 

@stop


@section('page-script')

$(document).ready(function() {


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

    var cl = <?php echo json_encode($sel_cl); ?>;
    var ch = <?php echo json_encode($sel_ch); ?>;
    var ds = <?php echo json_encode($sel_ds); ?>;
    var en = <?php echo json_encode($sel_en); ?>;
    var rg = <?php echo json_encode($sel_rg); ?>;
    var st = <?php echo json_encode($sel_st); ?>;

    var cat = <?php echo json_encode($sel_cat); ?>;
    var scat = <?php echo json_encode($sel_scat); ?>;
    var br = <?php echo json_encode($sel_br); ?>;



    $('#agencies').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select) {
          	updateclient();	
        }
    });

     $('#tag').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control',
    });
      $('#av').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control',
    });


    function updateclient(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', agencies: GetSelectValues($('select#agencies :selected'))},
            url: "{{ route('clientlist')}}",
            success: function(data){
                $('select#clients').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,cl ) > -1){
                      sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#clients')); 
                });
                $('select#clients').multiselect('rebuild');
                updatechannel();
           }
        });
    }

    $('#clients').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updatechannel
        }
    });

    function updatechannel(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', agencies: GetSelectValues($('select#agencies :selected')), clients: GetSelectValues($('select#clients :selected'))},
            url: "{{ route('channellist')}}",
            success: function(data){
                $('select#channels').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,ch ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#channels')); 
                });
                $('select#channels').multiselect('rebuild');
                updatedistributor();
           }
        });
    }

    $('#channels').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updatedistributor();
        }
    });

    function updatedistributor(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', agencies: GetSelectValues($('select#agencies :selected')), clients: GetSelectValues($('select#clients :selected')),channels: GetSelectValues($('select#channels :selected'))},
            url: "{{ route('distributorlist')}}",
            success: function(data){
                $('select#distributors').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,ds ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#distributors')); 
                });
                $('select#distributors').multiselect('rebuild');
                updateenrollment();
           }
        });
    }

    $('#distributors').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updateenrollment();
        }
    });

    function updateenrollment(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', agencies: GetSelectValues($('select#agencies :selected')),
                clients: GetSelectValues($('select#clients :selected')),
                channels: GetSelectValues($('select#channels :selected')),
                distributors: GetSelectValues($('select#distributors :selected'))},
            url: "{{ route('enrollmentlist')}}",
            success: function(data){
                $('select#enrollments').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,en ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#enrollments')); 
                });
                $('select#enrollments').multiselect('rebuild');
                updateregion();
           }
        });
    }

    $('#enrollments').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updateregion();
        }
    });

    function updateregion(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', agencies: GetSelectValues($('select#agencies :selected')),
                clients: GetSelectValues($('select#clients :selected')),
                channels: GetSelectValues($('select#channels :selected')),
                distributors: GetSelectValues($('select#distributors :selected')),
                enrollments: GetSelectValues($('select#enrollments :selected')),
            },
            url: "{{ route('regionlist')}}",
            success: function(data){
                $('select#regions').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,rg ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#regions')); 
                });
                $('select#regions').multiselect('rebuild');
                updatestores();
           }
        });
    }
    $('#regions').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updatestores();
        }
    });

    function updatestores(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', agencies: GetSelectValues($('select#agencies :selected')),
                clients: GetSelectValues($('select#clients :selected')),
                channels: GetSelectValues($('select#channels :selected')),
                distributors: GetSelectValues($('select#distributors :selected')),
                enrollments: GetSelectValues($('select#enrollments :selected')),
                regions: GetSelectValues($('select#regions :selected')),
            },
            url: "{{ route('storelist')}}",
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

    $('#stores').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
    });

    $('#divisions').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updatecategories();
        }
    });

    function updatecategories(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', divisions: GetSelectValues($('select#divisions :selected'))
            },
            url: "{{ route('categorylist')}}",
            success: function(data){
                $('select#categories').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,cat ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#categories')); 
                });
                $('select#categories').multiselect('rebuild');
                updatesubcategories();
           }
        });
    }


    $('#categories').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updatesubcategories();
        }
    });

    function updatesubcategories(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', divisions: GetSelectValues($('select#divisions :selected')),
              categories: GetSelectValues($('select#categories :selected'))
            },
            url: "{{ route('subcategorylist')}}",
            success: function(data){
                $('select#sub_categories').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,scat ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#sub_categories')); 
                });
                $('select#sub_categories').multiselect('rebuild');
                updatebrands();
           }
        });
    }


    $('#sub_categories').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
        onChange: function(option, checked, select){
            updatebrands();
        }
    });

    function updatebrands(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', divisions: GetSelectValues($('select#divisions :selected')),
              categories: GetSelectValues($('select#categories :selected')),
              sub_categories: GetSelectValues($('select#sub_categories :selected'))
            },
            url: "{{ route('brandlist')}}",
            success: function(data){
                $('select#brands').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,br ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#brands')); 
                });
                $('select#brands').multiselect('rebuild');
           }
        });
    }


    $('#brands').multiselect({
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
		buttonClass: 'form-control',
    });


    function updatebrands(){
        $.ajax({
            type: "POST",
            data: {type: '{{ $type }}', divisions: GetSelectValues($('select#divisions :selected')),
              categories: GetSelectValues($('select#categories :selected')),
              sub_categories: GetSelectValues($('select#sub_categories :selected'))
            },
            url: "{{ route('brandlist')}}",
            success: function(data){
                $('select#brands').empty();
                $.each(data.selection, function(i, text) {
                    var sel_class = '';
                    if($.inArray( i,br ) > -1){
                        sel_class = 'selected="selected"';
                    }
                    $('<option '+sel_class+' value="'+i+'">'+text+'</option>').appendTo($('select#brands')); 
                });
                $('select#brands').multiselect('rebuild');
           }
        });
    }

    var divag = $("select#agencies").val();
    if(divag !== null) {
        updateclient();
    }

    var divcat = $("select#divisions").val();
    if(divcat !== null) {
        updatecategories();
    }
  });

@stop