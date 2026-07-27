@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-calculator mr-1"></i> Proses Perhitungan Rekomendasi</h1>
</div>

<!-- 1. Matriks Keputusan (X) -->
<div class="card shadow mb-4 border-left-primary">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> 1. Matriks Keputusan (X)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Nama Siswa</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $alternatif->nama_siswa }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">{{ $matrixX[$alternatif->id_alternatif][$kriteria->id_kriteria] ?? 0 }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 2. Matriks Ternormalisasi (R) -->
<div class="card shadow mb-4 border-left-info">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> 2. Matriks Ternormalisasi (R)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-dark" width="100%" cellspacing="0">
                <thead class="bg-info text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Nama Siswa</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $alternatif->nama_siswa }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">{{ number_format($matrixR[$alternatif->id_alternatif][$kriteria->id_kriteria], 4) }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 3. Matriks Ternormalisasi Terbobot (V) -->
<div class="card shadow mb-4 border-left-success">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success"><i class="fa fa-table"></i> 3. Matriks Ternormalisasi Terbobot (V)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-dark" width="100%" cellspacing="0">
                <thead class="bg-success text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Nama Siswa</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $alternatif->nama_siswa }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">{{ number_format($matrixV[$alternatif->id_alternatif][$kriteria->id_kriteria], 4) }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 4. Solusi Ideal Positif (A+) & Negatif (A-) -->
<div class="card shadow mb-4 border-left-warning">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning"><i class="fa fa-star"></i> 4. Solusi Ideal Positif (A+) & Negatif (A-)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-dark" width="100%" cellspacing="0">
                <thead class="bg-warning text-white">
                    <tr>
                        <th>Solusi Ideal</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-weight-bold">Positif (A+)</td>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">{{ number_format($pis[$kriteria->id_kriteria], 4) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Negatif (A-)</td>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">{{ number_format($nis[$kriteria->id_kriteria], 4) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 5. Jarak Solusi Ideal Positif (D+) & Negatif (D-) -->
<div class="card shadow mb-4 border-left-secondary">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-secondary"><i class="fa fa-arrows-alt-h"></i> 5. Jarak Solusi Ideal (D+ & D-)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-dark" width="100%" cellspacing="0">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Nama Siswa</th>
                        <th class="text-center">D+</th>
                        <th class="text-center">D-</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $alternatif->nama_siswa }}</td>
                        <td class="text-center">{{ number_format($distPIS[$alternatif->id_alternatif], 4) }}</td>
                        <td class="text-center">{{ number_format($distNIS[$alternatif->id_alternatif], 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 6. Nilai Preferensi (V) -->
<div class="card shadow mb-4 border-left-danger">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-trophy"></i> 6. Nilai Preferensi (Ci)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-dark" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="10%" class="text-center">Rank</th>
                        <th>Nama Siswa</th>
                        <th class="text-center">D- / (D- + D+)</th>
                        <th class="text-center">Nilai Kedekatan (Ci)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($preference as $item)
                    <tr>
                        <td class="text-center font-weight-bold">{{ $item['rank'] }}</td>
                        <td>{{ $item['nama_siswa'] }}</td>
                        <td class="text-center">
                            {{ number_format($distNIS[$item['id_alternatif']], 4) }} / 
                            ({{ number_format($distNIS[$item['id_alternatif']], 4) }} + {{ number_format($distPIS[$item['id_alternatif']], 4) }})
                        </td>
                        <td class="text-center font-weight-bold text-primary">{{ number_format($item['score'], 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection