@extends('layout.default')


@section('content')

<div id="banner">
    <div class="row">
        <div class="col-lg-12">
            <h3>Role Group Lists</h3>
        </div>
    </div>
</div>
<hr/>

<div class="row">

	<div class="col-md-12">
		{!! Html::linkRoute('roles.create', 'Add Role Group', array(), ['class' => 'btn btn-primary btn-sm']) !!}
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Role Name</th>
						<th>Display Name</th>
						<th>Description</th>
						<th colspan="2"></th>
					<th></th>
					</tr>
				</thead>
				<tbody>
					@if(count($roles) > 0)
					@foreach($roles as $role)
					<tr> 
						<td>{{ $role->name }}</td> 
						<td>{{ $role->display_name }}</td> 
						<td>{{ $role->description }}</td> 
						<td>
							{!! Form::open(array('method' => 'DELETE', 'action' => array('RoleController@destroy', $role->id), 'class' => 'disable-button')) !!}                       
							{!! Form::submit('Delete', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to delete this role?')){return false;};")) !!}
							{!! Form::close() !!}
						</td>
						<td>
							{!! Html::linkRoute('roles.edit', 'Edit', array('id' => $role->id), ['class' => 'btn btn-primary btn-xs']) !!}
						</td>
					</tr> 
					@endforeach
					@else
					<tr> 
						<th colspan="7">No role found.</th> 
					</tr> 
					@endif
				</tbody> 
			</table> 
		</div>
	</div>
</div>
@endsection