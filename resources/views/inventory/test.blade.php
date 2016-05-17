@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
	<div class="row">
		<div class="col-lg-12">
			<h3>Assortment Report</h3>
		</div>
	</div>
</div>
<hr/>

{!! Form::open(array('route' => array('inventory.index', $type), 'class' => 'form-horizontal', 'method' => 'GET', 'id' => 'form_filtered')) !!}

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
                {!! Form::select('ag[]', $agencies, null, array('class' => 'form-control select_form', 'id' => 'agencies', 'multiple' => 'multiple')) !!}
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


{!! Paginate::show($items) !!}
{!! $items->render() !!}
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
			<td class="right">{{ $item->transaction_date }}</td>
		</tr>
		@endforeach
		@else
		<tr>
	  		<td colspan="14">No record found.</td>
		</tr>
		@endif
  	</tbody>
</table> 

@stop


@section('page-script')


@stop