@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>{!! $store->store_name !!} ASSORTMENT List</h3>
        </div>
    </div>
</div>
<hr/>
<div class="box box-default">
        {!! Form::open(array('method' => 'get','class' => 'bs-component')) !!}
        <div class="form-group">
            <label>Search</label>
          {!! Form::text('search',null,['class' => 'form-control', 'placeholder' => 'SKU Code / Description']) !!}
        </div>


        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        {!!  Form::close() !!}
    </div>
<br>

<a href="javascript:history.back()" ><button type="button" class="btn btn-default ">Back </button></a>
<!-- <hr> -->
<label class="pull-right">{{ $assortment->count() }} records found.</label>
<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>SKU Code</th>
            <th>Description</th>
            <th>Divison</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Brand</th>
            <th>Conversion</th>
            <th>LPBT</th>
            <th>IG</th>
            <th>Multiplier</th>
            <th>Min Stock</th>
        </tr>
    </thead>
  <tbody>
        @if(count($assortment) > 0)
        @foreach($assortment as $sku)
        <tr>
            <td>{{ $sku->item->sku_code }}</td>
            <td>{{ $sku->item->description }}</td>
            <td>{{ $sku->item->division->division }}</td>
            <td>{{ $sku->item->category->category }}</td>
            <td>{{ $sku->item->subcategory->sub_category }}</td>
            <td>{{ $sku->item->brand->brand }}</td>
            <td>{{ $sku->item->conversion }}</td>
            <td>{{ $sku->item->lpbt }}</td>
            <td>{{ $sku->ig }}</td>
            <td>{{ $sku->fso_multiplier }}</td>
            <td>{{ $sku->min_stock }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="11">No record found.</td>
        </tr>
        @endif
    </tbody>
</table> 

@stop


@section('page-script')



@stop