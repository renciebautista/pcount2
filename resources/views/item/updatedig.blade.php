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
{!! $items->render() !!}
{!! Paginate::show($items) !!}
<br>
<a href="{{ route('item.downloadupdatedig') }}" class="btn btn-success"><span class="glyphicon glyphicon-download-alt"></span> Export to Excel</a>
<a href="{{ route('item.removeig') }}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Remove Items</a>

<!-- <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid"> -->
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Store Code</th>
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
            <th>Date Updated</th>               
            <th></th>
        </tr>
    </thead>
  <tbody>    
        @if(count($items) > 0)
            @foreach($items as $item)                   
                <tr>
                    <td>{{ $item->store_code }}</td>
                    <td>{{ $item->store_name }}</td>
                    <td>{{ $item->sku_code }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->division }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->sub_category }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>{{ $item->conversion }}</td>
                    <td>{{ $item->min_stock }}</td>   
                    <td>{{ number_format($item->lpbt,2) }}</td>  
                    <td>{{ $item->ig }}</td> 
                    <td>{{ $item->updated_at }}</td>   
                    <td>
                    </td>
                </tr>                
            @endforeach
        @else
        <tr>
            <td colspan="13">No record found.</td>
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