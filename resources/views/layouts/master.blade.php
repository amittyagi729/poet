<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
      <link rel="icon" type="image/png" href="../assets/img/favicon.png">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <title>
         
      </title>
      <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
      <!--     Fonts and icons     -->
      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
      <!-- CSS Files -->
      <link href="{{ asset('front/assets/css/material-kit.css')}}" rel="stylesheet" />
      <!-- CSS Just for demo purpose, don't include it in your project -->
      <link href="{{ asset('front/assets/demo/demo.css')}}" rel="stylesheet" />
      <link href="{{ asset('front/assets/css/style.css')}}" rel="stylesheet" />
   </head>
   <body class="login-page sidebar-collapse">
          
          
            @yield('content')   
      <!--   Core JS Files   -->
      <script src="{{ asset('front/assets/js/core/jquery.min.js')}}" type="text/javascript"></script>
      <script src="{{ asset('front/assets/js/core/popper.min.js')}}" type="text/javascript"></script>
      <script src="{{ asset('front/assets/js/core/bootstrap-material-design.min.js')}}" type="text/javascript"></script>
      <script src="{{ asset('front/assets/js/plugins/moment.min.js')}}"></script>
      <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
      <script src="{{ asset('front/assets/js/plugins/bootstrap-datetimepicker.js')}}" type="text/javascript"></script>
      <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
      <script src="{{ asset('front/assets/js/plugins/nouislider.min.js')}}" type="text/javascript"></script>
      <!--  Google Maps Plugin    -->
      <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
      <script src="{{ asset('front/assets/js/material-kit.js')}}" type="text/javascript"></script>
   </body>
</html>
