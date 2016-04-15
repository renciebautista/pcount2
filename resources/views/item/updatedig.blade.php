@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Updated Item Inventory Goal List</h3>
        </div>
    </div>
</div>
<hr/>


<!-- <hr> -->

<label class="pull-right">{{ $items->count() }} records found.</label>
<!-- <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid"> -->
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Store</th>
            <th>SKU Code</th>
            <th>Description</th>
            <th>Divison</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Brand</th>
            <th>Conversion</th>
            <th>Min Stock</th>
            <th>LPBT</th>
            <th>IG</th>                   
            <th></th>
        </tr>
    </thead>
  <tbody>    
        @if(count($items) > 0)
            @foreach($items as $item)                   
                <tr>
                    <td>{{ $item->store->store_name }}</td>
                    <td>{{ $item->item->sku_code }}</td>
                    <td>{{ $item->item->description }}</td>
                    <td>{{ $item->item->division->division }}</td>
                    <td>{{ $item->item->category->category }}</td>
                    <td>{{ $item->item->subcategory->sub_category }}</td>
                    <td>{{ $item->item->brand->brand }}</td>
                    <td>{{ $item->item->conversion }}</td>
                    <td>{{ $item->min_stock }}</td>   
                    <td>{{ $item->item->lpbt }}</td>  
                    <td>{{ $item->ig }}</td>                   
                    <td>
                    </td>
                </tr>                
            @endforeach
        @else
        <tr>
            <td colspan="12">No record found.</td>
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