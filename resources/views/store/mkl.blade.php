@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Store MKL List</h3>
        </div>
    </div>
</div>
<hr/>


<a href="javascript:history.back()" ><button type="button" class="btn btn-default ">Back </button></a>
<!-- <hr> -->
<label class="pull-right">{{ $mkl->count() }} records found.</label>
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
        </tr>
    </thead>
  <tbody>
        @if(count($mkl) > 0)
        @foreach($mkl as $sku)
        <tr>
            <td>{{ $sku->item->sku_code }}</td>
            <td>{{ $sku->item->description }}</td>
            <td>{{ $sku->item->division->division }}</td>
            <td>{{ $sku->item->category->category }}</td>
            <td>{{ $sku->item->subcategory->sub_category }}</td>
            <td>{{ $sku->item->brand->brand }}</td>
            <td>{{ $sku->item->conversion }}</td>
            <td>{{ $sku->item->lpbt }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8">No record found.</td>
        </tr>
        @endif
    </tbody>
</table> 

@stop


@section('page-script')



@stop