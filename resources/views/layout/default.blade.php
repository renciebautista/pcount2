
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

			<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	   		
	   		<ul class="nav navbar-nav">
            	<li class="dropdown">
              		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="nav_label_left"><b>Utilities</b><span class="caret"></span></a>
              		<ul class="dropdown-menu">
                		<li>{!! Html::linkRoute('import.masterfile', 'Import Masterfile', array(), array('class' => 'submenu-font', 'id' => 'nav_sub_label')) !!}</li>
               		</ul>
            	</li>
          	</ul>

          	<ul class="nav navbar-nav">
            	<li class="dropdown">
              		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="nav_label_left"><b>Reports</b><span class="caret"></span></a>
              		<ul class="dropdown-menu">
                		<li>{!! Html::linkRoute('inventory.index', 'Posted Transaction Report', array(), array('class' => 'submenu-font', 'id' => 'nav_sub_label')) !!}</li>
               		</ul>
            	</li>
          	</ul>
		  	
		  	<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
			  		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="nav_label_right">Administrator <span class="caret"></span></a>
			  		<ul class="dropdown-menu">
						<li><a href="#">Sign-out</a></li>
			  		</ul>
				</li>
		  	</ul>
		</div><!-- /.navbar-collapse -->

			
		   
		</div><!-- /.container-fluid -->
	</nav>	

	<div class="container">
	  <div class="content_container">
		@yield('content') 
	  </div>
	</div>

	</div>
		<br/>

		<!-- script -->
		{!! Html::script('js/jquery-1.11.3.min.js') !!}
		{!! Html::script('js/bootstrap.js') !!}
		{!! Html::script('js/bootstrap-multiselect.js') !!}
		{!! Html::script('js/bootstrap-multiselect-collapsible-groups.js') !!}
		{!! HTML::script('js/datepicker/datepicker-ui.js') !!}

		<script type="text/javascript">
		$(document).ready(function() {
			@section('page-script')

			@show
		});
	</script>

	</body>

	
</html>
