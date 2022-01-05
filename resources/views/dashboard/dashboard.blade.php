<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="author" content="{{ env('APP_DEVELOPER') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ env('APP_DEVELOPER') }} - {{ env('APP_NAME') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/nutech.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">

    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/assets/css/style.css">

    @yield('custom_css')

    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    @include('dashboard.layouts.header')

    @include('dashboard.layouts.sidebar')

    @yield('content')

    @include('dashboard.layouts.footer')

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('dashboard') }}/app-assets/js/core/app-menu.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('dashboard') }}/app-assets/js/scripts/forms/form-select2.js"></script>
    {{-- <script src="{{ asset('dashboard') }}/app-assets/js/scripts/tables/table-datatables-advanced.js"></script> --}}
    <!-- END: Page JS-->

    <!-- Start: Custom JS-->
    @yield('custom_js')
    <!-- End: Custom JS-->


    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });

        $(document).ready(function () {
            $(function() {
                var url = window.location.href;
                $('.navigation-main li').each(function() {
                    if ($('a',this).attr('href') == url) {
                        $(this).addClass('active');
                    }
                });
            });
        });

        $('#provinsi_id').on('change', function(event){
            let provinsi_id = $(this).val();
            $.ajax({
                type: "GET",
                url: '/kota/' + provinsi_id,
                dataType: "json",
                success: function (response) {
                    $('#kota_id').empty();
                    $.each(response, function (key, value) {
                        $('#kota_id').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });

    </script>

</body>
<!-- END: Body-->

</html>
