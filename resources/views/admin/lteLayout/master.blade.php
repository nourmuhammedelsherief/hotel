<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="icon" href="{{ URL::asset('/uploads/img/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('lte/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('lte/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('lte/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('lte/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('lte/plugins/summernote/summernote-bs4.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
     @if(app()->getLocale() == 'ar')
         <!-- Bootstrap 4 RTL -->
             <link rel="stylesheet" href="{{asset('https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css')}}">
        <!-- Custom style for RTL -->
             <link rel="stylesheet" href="{{asset('lte/dist/css/custom.css')}}">
    @endif

    <link rel="stylesheet" href="{{asset('lte/dist/css/global.css')}}">
    @if(app()->getLocale() == 'en')
        <link rel="stylesheet" href="{{asset('lte/dist/css/style_ltr.css')}}">
    @endif
    @yield('style')
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed" dir="{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}">
<div class="wrapper">

@include('admin.lteLayout.header')

@include('admin.lteLayout.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>@lang('messages.copyRight')
            <a href="https://tqnee.com.sa"> @lang('messages.tqneeCompany') </a>
            &copy; {{\Carbon\Carbon::now()->format('Y')}}
            .</strong>
        @lang('messages.all_rights_reserved').
        <div class="float-right d-none d-sm-inline-block">

        </div>
    </footer>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <div id="sidebar-overlay"></div>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('lte/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('lte/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
@if(app()->getLocale() == 'ar')
    <!-- Bootstrap 4 rtl -->
    <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
@endif
<!-- Bootstrap 4 -->
<script src="{{asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('lte/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('lte/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('lte/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('lte/plugins/jqvmap/maps/jquery.vmap.world.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('lte/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('lte/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('lte/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('lte/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('lte/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('lte/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('lte/dist/js/demo.js')}}"></script>
<script>
    $('.custom-switch.off .text').html('Off');
    $(function(){
        $('#btn-sidebar-menu').on('click' , function(){

            var tag = $('body.sidebar-mini');
            console.log(tag);
            if(tag.hasClass('sidebar-open')){
                tag.removeClass('sidebar-open').addClass('sidebar-collapse');
                // $('#sidebar-overlay').css('display' , 'none');
            }else if(tag.hasClass('sidebar-collapse')){
                tag.removeClass('sidebar-collapse').addClass('sidebar-open');
                // console.log(tag);
                // $('#sidebar-overlay').css('display' , 'block');
            }
            else{
                if(window.innerWidth > 800)
                    tag.addClass('sidebar-collapse').removeClass('sidebar-open');
                else tag.addClass('sidebar-open').removeClass('sidebar-collapse');
                // console.log(window.innerWidth);
                // $('#sidebar-overlay').css('display' , 'block');
            }
        });
        $('#sidebar-overlay').on('click' , function(){
            console.log('test');
            $('body.sidebar-mini').removeClass('sidebar-open').addClass('sidebar-collapse');
        });
        $('.custom-switch').on('click' , function(){
            var tag = $(this);
            if(tag.hasClass('off')){
                window.location.replace(tag.data('url_off'));
                tag.removeClass('off');
            }else {
                window.location.replace(tag.data('url_on'));
                tag.addClass('off');
            }
        });
    });
</script>
@yield('scripts')
@stack('scripts')
</body>
</html>
