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


{!! Form::open(array('method' => 'get','class' => 'bs-component')) !!}
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
        <label>Search</label>
      {!! Form::text('search',null,['class' => 'form-control', 'placeholder' => 'Keywords']) !!}
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
        <label>Role</label>
        {!! Form::select('role',$roles,null, array('class' => 'form-control', 'placeholder' => 'All Role')) !!}
    </div>
  </div>
</div>

<div class="box-footer">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>

 {!!  Form::close() !!} 


<!-- <hr> -->
<br>


{!! Html::linkRoute('store_user.create', 'Add User', array(), ['class' => 'btn btn-success btn-sm pull-left']) !!}

<label class="pull-right">{{ $users->count() }} records found.</label>

<div class="row">
    <div class="col-md-12">
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th></th> 
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
                                <td>{{ $user->status()}}</td>
                                @if($user->roles[0]->name == 'field')
                                <td>{!! link_to_action('StoreUserController@storelist', 'Store Mapping', $user->id, ['class' => 'btn btn-xs btn btn-success']) !!}</td>                    
                                @else
                                <td></td>
                                @endif
                                <td>{!! link_to_action('StoreUserController@edit', 'Edit', $user->id, ['class' => 'btn btn-xs btn btn-primary']) !!}</td>                    

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