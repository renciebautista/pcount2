
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Ahead PCount System</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<!-- css -->
		{!! Html::style('css/bootstrap-1.css') !!}
		{!! Html::style('css/bootstrap.min.css') !!}
		{!! Html::style('css/backend-style.css') !!}
		{!! Html::style('css/bootstrap-multiselect.css') !!}
		{!! HTML::style('css/datepicker/datepicker.css') !!}

		{!! HTML::style('css/pcount.css') !!}
		{!! HTML::style('css/bootstrap-switch.css') !!}
		
	</head>

	<body>
		
	<nav id="nav" class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ route('dashboard.index') }}" id="nav_systemname_label"><img src="{{ URL::asset('img/unilever-logo.jpg') }}" style="height: 50px; width: 55px; margin-top: -15px;"></img></a>
			</div>

		

			
		   
		</div><!-- /.container-fluid -->
	</nav>	

	<div class="container">
		@yield('content') 
	</div>

	</div>

	</body>

	
</html>
