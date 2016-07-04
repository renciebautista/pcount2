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
            <th colspan="2"></th>
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
                        {!! link_to_action('ItemController@othercode', 'Other Barcode', $item->id, ['class' => 'btn btn-xs btn btn-primary']) !!} &nbsp;{!! link_to_action('ItemController@edit', 'Edit', $item->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
                    </td>
                    <td>
                        {!! Form::open(array('method' => 'DELETE', 'action' => array('ItemController@destroy', $item->id), 'class' => 'disable-button')) !!}                       
                        {!! Form::submit('Remove', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to delete this item?')){return false;};")) !!}
                        {!! Form::close() !!}
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