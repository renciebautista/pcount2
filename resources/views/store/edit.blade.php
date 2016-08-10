@extends('layout.default')

@section('content')

@include('shared.notifications')
<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Store</h3>
        </div>
    </div>
</div>
<hr/>

 {!! Form::open(['route' => ['store.update', $store->id], 'method' => 'PUT', 'files'=>true,'class' => 'form-horizontal']) !!}
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
						{!!Form::label('user_id', 'User',array('class' => 'col-lg-4 control-label'))!!}
						<div class="col-lg-8">
	                	{!! Form::select('user_id',$alluser, $user->user_id, ['class' => 'form-control'])!!}
                        {!!Form::hidden('username',$user->username)!!}
                        {!!Form::hidden('userid',$user->user_id)!!}
   			</div>
        </div>
    </div>

<div class="col-lg-4">
        <div class="form-group">
						{!!Form::label('client_id', 'Client',array('class' => 'col-lg-4 control-label'))!!}
						<div class="col-lg-8">
	                	{!! Form::select('client_id',$client, $store->client_name, ['class' => 'form-control'])!!}
   			</div>
        </div>
    </div>
<div class="col-lg-4">
        <div class="form-group">
                        {!!Form::label('store_code', 'Store Code',array('class' => 'col-lg-4 control-label'))!!}
                        <div class="col-lg-8">
                         {!! Form::text('store_code', $store->store_code,array('placeholder'=>'Store Code', 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>



    </div>
 
<div class="row">
 
<div class="col-lg-4">
        <div class="form-group">
						{!!Form::label('enrollment_id', 'Enrollment',array('class' => 'col-lg-4 control-label'))!!}
						<div class="col-lg-8">
	                	{!! Form::select('enrollment_id',$enrollment, $store->enrollment_id, ['class' => 'form-control'])!!}
   			</div>
        </div>
    </div>
<div class="col-lg-4">
        <div class="form-group">
						{!!Form::label('channel_id', 'Channel',array('class' => 'col-lg-4 control-label'))!!}
						<div class="col-lg-8">
	                	{!! Form::select('channel_id',$channel, $store->channel, ['class' => 'form-control'])!!}
   			</div>
        </div>
    </div>
   
 <div class="col-lg-4">
        <div class="form-group">
                        {!!Form::label('store_code_psup', 'Store Code Psup',array('class' => 'col-lg-4 control-label'))!!}
                        <div class="col-lg-8">
                         {!! Form::text('store_code_psup', $store->store_code_psup,array('placeholder'=>'Store Code Psup' , 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-lg-4">
        <div class="form-group">
						{!!Form::label('distributor_id', 'Distributor',array('class' => 'col-lg-4 control-label'))!!}
						<div class="col-lg-8">
	                	{!! Form::select('distributor_id',$distributor, $store->distributor_id, ['class' => 'form-control'])!!}
   			</div>
        </div>
    </div>


<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('customer_id','Customer',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
              {!! Form::select('customer_id',$customer, $store->customer_name, ['class' => 'form-control'])!!}
            </div>
        </div>
        </div>
<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('status','Status',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
            {!! Form::select('status', $status, $store->active, ['class' => 'form-control'])!!}
            </div>
        </div>
        </div>




</div>
<div class="row">
 
<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('store_id','Store ID',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
               {!! Form::text('store_id', $store->storeid,array('placeholder'=>'Store ID', 'class' => 'form-control'))!!}
            </div>
        </div>
        </div>
        
<div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('region_id','Region',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
              {!! Form::select('region_id',$region, $store->region_short, ['class' => 'form-control'])!!}
            </div>
        </div>
    </div>

<div class="col-lg-4">
        <div class="form-group">
                        {!!Form::label('area_id', 'Area',array('class' => 'col-lg-4 control-label'))!!}
                        <div class="col-lg-8">
                        {!! Form::select('area_id',$area, $store->area_id, ['class' => 'form-control'])!!}
            </div>
        </div>
    </div>

    </div>
    <div class="row">
 

     <div class="col-lg-4">
        <div class="form-group">
                {!! Form::label('store_name','Store name',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
               {!! Form::text('store_name', $store->store_name,array('placeholder'=>'Store name', 'class' => 'form-control'))!!}
            </div>
        </div>
    </div>


<div class="col-lg-4">
           <div class="form-group">
                {!! Form::label('agency_id','Agency',array('class' => 'col-lg-4 control-label')) !!}
            <div class="col-lg-8">
              {!! Form::select('agency_id',$agency, $store->agency_name, ['class' => 'form-control'])!!}
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
@stop