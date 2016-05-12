@extends('layout.default')

@section('content')

@include('shared.notifications')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>User List</h3>           
        </div>
    </div>
</div>
<hr/>

<div class="row">
    <div class="col-md-12">
        {!! Html::linkRoute('store_user.create', 'Add User', array(), ['class' => 'btn btn-primary btn-sm']) !!}
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th></th>            
                    </tr>
                </thead>
                <tbody>    
                    @if(count($users) > 0)
                        @foreach($users as $user)                
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles[0]->name }}</td>
                                @if($user->roles[0]->name == 'field')
                                <td>{!! link_to_action('StoreUserController@storelist', 'Stores', $user->id, ['class' => 'btn btn-xs btn btn-primary']) !!}</td>                    
                                @else
                                <td></td>
                                @endif
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
    </div>
</div>

@stop
@section('page-script')
@stop