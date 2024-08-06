<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="#">
        <link rel="icon" type="image/png" href="#">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<title> @section('title') @show - ALM </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        @include('favicon')
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Bodoni+serif" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="{{ URL::asset('assets/material/css/material-kit.css?v=2.0.7')}}" rel="stylesheet" />
        <link href="{{ URL::asset('assets/material/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" />

        <link href="{{ URL::asset('assets/css/mymain.css')}}" rel="stylesheet" />
        <link href="{{ URL::asset('assets/css/customstyle.css')}}" rel="stylesheet" />
        @yield('header_styles')
    </head>

    <body class="bg-white">
        <div class="">
            <div class="row no-gutters align-items-center">
                
                 @yield('content')

                
            </div>
        </div>

        <!--   Core JS Files   -->
        <script src="{{ URL::asset('assets/material/js/core/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/material/js/core/popper.min.js')}}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/material/js/core/bootstrap-material-design.min.js')}}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/material/js/plugins/moment.min.js')}}"></script>
        <!--    Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
        <script src="{{ URL::asset('assets/material/js/plugins/bootstrap-datetimepicker.js')}}" type="text/javascript"></script>
        <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
        <script src="{{ URL::asset('assets/material/js/plugins/nouislider.min.js')}}" type="text/javascript"></script>
        <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
        <script src="{{ URL::asset('assets/material/js/material-kit.js?v=2.0.7')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/jquery.validate.min.js')}}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/app.js')}}" type="text/javascript"></script>
         @yield('footer_scripts')
    </body>

</html>