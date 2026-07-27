@extends('layouts.app')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-primary alert-dismissible shadow fade show" role="alert" style="border-radius: 15px; border: none; background: linear-gradient(45deg, #4e73df, #224abe); color: white;">
            <i class="fas fa-school mr-2"></i> Selamat datang di Sistem Rekomendasi Lomba Siswa SD, <strong>{{ Auth::user()->name }}!</strong>.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row">

    <!-- Card Kriteria -->
    {{-- <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('kriteria.index') }}" class="text-decoration-none">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Master Data</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Kriteria Penilaian</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cube fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div> --}}

    <!-- Card Sub Kriteria -->
    {{-- <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('sub-kriteria.index') }}" class="text-decoration-none">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Master Data</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Sub Kriteria</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div> --}}

    <!-- Card Alternatif -->
    {{-- <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('alternatif.index') }}" class="text-decoration-none">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Master Data</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Data Alternatif (Siswa)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div> --}}

    <!-- Card Penilaian -->
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('penilaian.index') }}" class="text-decoration-none">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Input Data</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Data Penilaian</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card Perhitungan -->
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ url('perhitungan') }}" class="text-decoration-none">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Proses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Data Perhitungan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Card Hasil Akhir -->
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('hasil.index') }}" class="text-decoration-none">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Output</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Data Hasil Akhir</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

@endsection