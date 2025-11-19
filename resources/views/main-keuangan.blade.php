<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="auth-status" content="{{ Auth::guard('keuangan')->check() ? 'true' : 'false' }}">
    <title>SPMB Keuangan</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/bn.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('assets2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/css/main.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('keuangan.partials.sidebar')
        
        <div id="content-wrapper" class="d-flex flex-column">
            @yield('content-keuangan')
            
            @include('keuangan.partials.footer')
        </div>
    </div>

    <script src="{{ asset('assets2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets2/js/sb-admin-2.min.js') }}"></script>
    

</body>

</html>