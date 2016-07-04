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


<div class="box box-default">
        {!! Form::open(array('method' => 'get','class' => 'bs-component')) !!}
        <div class="form-group">
            <label>Search</label>
          {!! Form::text('search',null,['class' => 'form-control', 'placeholder' => 'Keywords']) !!}
        </div>

        <div class="form-group">
            <label class="radio-inline">
                {!! Form::radio('status', 1, true) !!} Active
            </label>
            <label class="radio-inline">
                {!!  Form::radio('status', 2) !!} In-active         
            </label>
            <label class="radio-inline">
                {!! Form::radio('status', 3) !!} All    
            </label>
        </div>


        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        {!!  Form::close() !!}
    </div>


<!-- <hr> -->

{!! Paginate::show($items) !!}
{!! $items->render() !!}

<!-- <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline" role="grid"> -->
<table class="table table-striped table-hover">
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
            <th>Status</th>          
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
                    <td>{{ $item->status() }}</td>                  
                    <td>
                        {!! link_to_action('ItemController@othercode', 'Other Barcode', $item->id, ['class' => 'btn btn-xs btn btn-primary']) !!} &nbsp;{!! link_to_action('ItemController@edit', 'Edit', $item->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
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