<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Lomba Siswa Sekolah Dasar</title>
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
</head>

<body class="bg-gradient-primary">
    <nav class="navbar navbar-light bg-white shadow mb-5">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-chart-line fa-lg mr-2 text-primary"></i>
                <span class="h4 font-weight-bold text-gray-800 mb-0">Sistem Rekomendasi Lomba Siswa Sekolah Dasar</span>
            </a>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>

</html>