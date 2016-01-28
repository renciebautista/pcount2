@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Other Barcode</h3>
        </div>
    </div>
</div>
<hr/>



<!-- <hr> -->
<label class="pull-right">{{ $items->count() }} records found.</label>
<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>Area</th>
            <th>SKU Code</th>
            <th>Other Barcode</th>
        </tr>
    </thead>
  <tbody>
        @if(count($items) > 0)
        @foreach($items as $item)
        <tr>
            <td>{{ $item->area->area }}</td>
            <td>{{ $item->item->sku_code }}</td>
            <td>{{ $item->other_barcode }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="2">No record found.</td>
        </tr>
        @endif
    </tbody>
</table> 

@stop


@section('page-script')



@stop