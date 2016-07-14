@extends('layout.default')

@section('content')

@include('shared.notifications')
<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Item</h3>
        </div>
    </div>
</div>
<hr/>

 {!! Form::open(['route' => ['item.update', $item->id], 'method' => 'PUT', 'files'=>true,'class' => 'form-horizontal']) !!}
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('sku_code','SKU Code',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
               {!! Form::text('sku_code', $item->sku_code,array('placeholder'=>'SKU Code', 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('bacrode','Barcode',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
               {!! Form::text('barcode', $item->barcode,array('placeholder'=>'Barode', 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('description','Description',array('class' => 'col-lg-4 control-label')) !!}

            <div class="col-lg-8">
               {!! Form::textarea('description', $item->description,array('placeholder'=>'Description', 'class' => 'form-control','rows'=> 2))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('description_long','Description Long',array('class' => 'col-lg-4 control-label')) !!}

            <div class="col-lg-8">
               {!! Form::textarea('description_long', $item->description_long,array('placeholder'=>'Description Long', 'class' => 'form-control','rows'=> 2))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('division','Division',array('class' => 'col-lg-4 control-label')) !!}

            <div class="col-lg-8">
            {!! Form::select('division', $divisions, $sel_dv, array('class' => 'form-control select_form', 'id' => 'divisions')) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('category', 'Category', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="categories" name="category"  ></select>                
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('sub_category', 'Sub Category', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                <select class="form-control"  id="sub_categories" name="sub_category"  ></select>              
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-lg-4">
        <div class="form-group">
                 {!! Form::label('brand', 'Brand', array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
                {!! Form::select('brand_id',$brand, $item->brand_id, ['class' => 'form-control'])!!}    
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('conversion','Conversion',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
               {!! Form::text('conversion', $item->conversion,array('placeholder'=>'Conversion', 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('lpbt','LPBT',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
               {!! Form::text('lpbt', $item->lpbt,array('placeholder'=>'LPBT', 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('status','Status',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
            {!! Form::select('status', $status, $item->active, ['class' => 'form-control'])!!}
            </div>
        </div>
        </div>
        </div>
<div class="row">
    
    <div class="col-lg-4">
        <div class="form-group">
            <div class="row">
                <div class="col-lg-4">
                </div>
                <div class="col-lg-8">
                    <a href="javascript:history.back()" ><button type="button" class="btn btn-default ">Back </button>
                    </a>
                     <button type="submit" name="submit" value="submit" class="btn btn-info" ><span ></span>Save</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
    </div>

    <div class="col-lg-4">
        
    </div>
{!! Form::close() !!}










@stop


@section('page-script')
 var cat = <?php echo json_encode($sel_cat); ?>;
 var scat = <?php echo json_encode($sel_scat); ?>;


$('#item_type').multiselect({  
        maxHeight: 200,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        buttonWidth: '100%',
        buttonClass: 'form-control',
    });

  $('#divisions').multiselect({
        maxHeight: 200,
      
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
            data: {divisions: GetSelectValues($('select#divisions :selected'))
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
            data: { divisions: GetSelectValues($('select#divisions :selected')),
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
            data: { divisions: GetSelectValues($('select#divisions :selected')),
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
@stop