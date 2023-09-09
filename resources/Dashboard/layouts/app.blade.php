<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ URL::asset('/plugins/fontawesome-free/css/all.min.css') }}">

    {{-- @vite('public/plugins/fontawesome-free/css/all.min.css') --}}
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="dist/css/adminlte.min.css"> --}}
    <link rel="stylesheet" href="{{ URL::asset('dist/css/adminlte.min.css') }}">

    {{-- @vite('public/dist/css/adminlte.min.css') --}}
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    {{-- <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css"> --}}
    <link rel="stylesheet" href="{{ URL::asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    {{-- @vite('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') --}}
    <!-- overlayScrollbars -->
    {{-- <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css"> --}}
    <link rel="stylesheet" href="{{ URL::asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    {{-- @vite('public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') --}}
    <!-- Bootstrap 4 RTL -->
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">
    <!-- Custom style for RTL -->
    {{-- <link rel="stylesheet" href="dist/css/custom.css"> --}}
    <link rel="stylesheet" href="{{ URL::asset('/dist/css/custom.css') }}">

    {{-- @vite('public/dist/css/custom.css') --}}
    @stack('headerScripts')
    <!-- Scripts -->

</head>

<body class="sidebar-mini layout-fixed sidebar-open">
    <!-- Navbar -->
    <x-navbars.top />
    <!-- /.navbar -->
    <x-navbars.aside />
    <main class="content-wrapper" class="py-4">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $title ?? 'title' }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        {{ $breadcrumb ?? 'breadcrumb' }}
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        {{ $slot ?? '' }}
    </main>
    <script src="{{ URL::asset('/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ URL::asset('/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('/dist/js/resolveConflict.js') }}"></script>
    <script src="{{ URL::asset('/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    {{-- @vite('public/plugins/jquery/jquery.min.js') --}}
    <!-- AdminLTE App -->
    {{-- @vite('public/dist/js/adminlte.min.js') --}}

    {{-- @vite('public/plugins/jquery-ui/jquery-ui.min.js') --}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    {{-- @vite('public/dist/js/resolveConflict.js') --}}
    <!-- Bootstrap 4 rtl -->
    {{-- <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js" defer></script> --}}
    <script src="{{ URL::asset('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" {{-- @vite('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') --}} <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('bodyScripts')

</body>

</html>
