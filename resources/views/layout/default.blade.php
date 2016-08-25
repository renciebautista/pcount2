
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>PCount System</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<!-- css -->
		{!! Html::style('css/bootstrap-1.css') !!}
		{!! Html::style('css/bootstrap.min.css') !!}
		{!! Html::style('css/backend-style.css') !!}
		{!! Html::style('css/bootstrap-multiselect.css') !!}
		{!! HTML::style('css/datepicker/datepicker.css') !!}
        {!! HTML::style('dataTables/dataTables.bootstrap.css') !!}
        {!! HTML::style('css/bootstrap-switch.css') !!}
		{!! HTML::style('css/pcount.css') !!}
		
	</head>

	<body>
		 <input type="hidden" name="_token" value="{{{ csrf_token() }}}"/>
	<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- <a class="navbar-brand" href="#">Project name</a> -->
          				<a class="navbar-brand" href="{{ route('dashboard.index') }}" id="nav_systemname_label"><img src="{{ URL::asset('img/unilever-logo.jpg') }}" style="height: 50px; width: 55px; margin-top: -15px;"></img></a>

        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            @if(Entrust::hasRole('admin'))
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Maintenance <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>{!! Html::linkRoute('store.index', 'Stores', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('item.index', 'Items', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('store_user.index', 'Users', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('roles.index', 'Roles', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('apk.index', 'Apk', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('testapk.index', 'Test Apk', array(), array()) !!}</li>
                </ul>
            </li>
            @endif
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li>{!! Html::linkRoute('inventory.index', 'MKL Posted Transaction Report', array('type' => 'mkl'), array()) !!}</li>
                <li>{!! Html::linkRoute('so.area', 'MKL SO Per Area Report', array('type' => 'mkl'), array()) !!}</li>
                <li>{!! Html::linkRoute('so.store', 'MKL SO Per Store Report', array('type' => 'mkl'), array()) !!}</li>
                <li>{!! Html::linkRoute('osa.area', 'MKL OSA Per Area Report', array('type' => 'mkl'), array()) !!}</li>
                <li>{!! Html::linkRoute('osa.store', 'MKL OSA Per Store Report', array('type' => 'mkl'), array()) !!}</li>
                <li>{!! Html::linkRoute('oos.sku', 'MKL OOS SKU Report', array('type' => 'mkl'), array()) !!}</li>
                <li role="separator" class="divider"></li>
                <li>{!! Html::linkRoute('inventory.index', 'Assortment Posted Transaction Report', array('type' => 'assortment'), array()) !!}</li>
                <li>{!! Html::linkRoute('so.area', 'Assortment SO Per Area Report', array('type' => 'assortment'), array()) !!}</li>
                <li>{!! Html::linkRoute('so.store', 'Assortment SO Per Store Report', array('type' => 'assortment'), array()) !!}</li>
                <li>{!! Html::linkRoute('osa.area', 'Assortment OSA Per Area Report', array('type' => 'assortment'), array()) !!}</li>
                <li>{!! Html::linkRoute('osa.store', 'Assortment OSA Per Store Report', array('type' => 'assortment'), array()) !!}</li>
                <li>{!! Html::linkRoute('oos.sku', 'Assortment OOS SKU Report', array('type' => 'assortment'), array()) !!}</li>
                <li>{!! Html::linkRoute('assortment.index', 'Assortment Compliance Report', array(), array()) !!}</li>
                <li role="separator" class="divider"></li>
                <li>{!! Html::linkRoute('compliance.index', 'Total Compliance Report', array(), array()) !!}</li>
                @if(Entrust::hasRole('admin'))
                
                @endif
              </ul>
            </li>
            @if(Entrust::hasRole('admin'))
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Utilities <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>{!! Html::linkRoute('import.masterfile', 'Import Masterfile', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('import.remapping', 'Import Store User Remapping', array(), array()) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! Html::linkRoute('export.stores', 'Export Store Masterfile', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('export.items', 'Export Items Masterfile', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('export.othercode', 'Export Item Other Code Masterfile', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('export.storeosa', 'Export Store OSA Item Masterfile', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('export.storeassortment', 'Export Store Assortment Masterfile', array(), array()) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! Html::linkRoute('item.updatedig', 'Updated Inventory Goal', array(), array()) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! Html::linkRoute('history.posting', 'Posting History Report', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('device_users.index', 'Currently Logged in Device', array(), array()) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! Html::linkRoute('devices.index', 'Device Lists', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('deviceerror.index', 'Device Error Report', array(), array()) !!}</li>
                    <li role="separator" class="divider"></li>
                    <li>{!! Html::linkRoute('store.invalid', 'Invalid Store List', array(), array()) !!}</li>
                    <li>{!! Html::linkRoute('mapping.invalid', 'Invalid Mapping List', array(), array()) !!}</li>
                     <li>{!! Html::linkRoute('backup.list', 'Backup List', array(), array()) !!}</li>

                </ul>
            </li>
            @endif
            @if(Entrust::hasRole('admin'))
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Settings <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>{!! Html::linkRoute('settings.index', 'Settings', array(), array()) !!}</li>
                </ul>
            </li>
            @endif
          </ul>
          <ul class="nav navbar-nav navbar-right">
          	<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ ucwords(strtolower(Auth::user()->name)) }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>{!! Html::linkRoute('auth.logout', 'Sign-out', array(), array()) !!}</li>
                </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    @if (App::environment('test')) 
      <h2 style="color:#F00; text-align:center">Testing Enviroment</h2>
    @endif
     
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
        });
    </script>
    
		<script type="text/javascript">
		$(document).ready(function() {
        function GetSelectValues(select) {
        var foo = []; 
        select.each(function(i, selected){ 
          foo[i] = $(selected).val(); 
        });
        return foo;
    }
    
			@section('page-script')

			@show
		});
	</script>

	</body>


	
</html>
