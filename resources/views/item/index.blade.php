@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Item List</h3>
        </div>
    </div>
</div>
<hr/>



<!-- <hr> -->
<label class="pull-right">{{ $items->count() }} records found.</label>
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
            <th></th>
        </tr>
    </thead>
  <tbody>
        @if(count($items) > 0)
        @foreach($items as $item)
        <tr>
            <td>{{ $item->sku_code }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->division->division }}</td>
            <td>{{ $item->category->category }}</td>
            <td>{{ $item->subcategory->sub_category }}</td>
            <td>{{ $item->brand->brand }}</td>
            <td>{{ $item->conversion }}</td>
            <td>{{ $item->lpbt }}</td>
            <td>
                {!! link_to_action('ItemController@othercode', 'Other Barcode', $item->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
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

@stop


@section('page-script')



@stop