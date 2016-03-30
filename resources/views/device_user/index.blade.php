@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Device Users Currently Logged</h3>           
        </div>
    </div>
</div>
<hr/>
 
<label class="pull-right">{{ $users->count() }} records found.</label>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>User</th>
            <th>Device ID</th>
            <th>Last Login</th>
            <th></th>            
        </tr>
    </thead>
    <tbody>    
        @if(count($users) > 0)
            @foreach($users as $user)                
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->device_id }}</td>
                    <td>{{ $user->last_login }}</td>
                    <td>{!! link_to_action('DeviceUserController@logOut', 'Log Out', $user->id, ['class' => 'btn btn-xs btn btn-primary']) !!}</td>                    
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
@stop