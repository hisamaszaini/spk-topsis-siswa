@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-calculator mr-1"></i> Data Perhitungan</h1>
</div>

<!-- 1. Matriks Keputusan (X) -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks Keputusan (X)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-left">Nama Alternatif</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $alternatif->nama_alternatif }}</td>
                        @foreach($kriterias as $kriteria)
                        @php
                        $nilai = $alternatif->penilaian->where('id_kriteria', $kriteria->id_kriteria)->first();
                        @endphp
                        <td class="text-center">{{ $nilai ? $nilai->nilai : 0 }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 2. Matriks Ternormalisasi (R) -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks Ternormalisasi (R)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-left">Nama Alternatif</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $alternatif->nama_alternatif }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">
                            {{ isset($normalizedMatrix[$alternatif->id_alternatif][$kriteria->id_kriteria]) ? round($normalizedMatrix[$alternatif->id_alternatif][$kriteria->id_kriteria], 4) : 0 }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 3. Bobot Preferensi (W) -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Bobot Preferensi (W)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->kode_kriteria }} ({{ $kriteria->jenis }})</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($kriterias as $kriteria)
                        <td class="text-center">{{ $kriteria->bobot }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 4. Perhitungan (V) -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Perhitungan (V)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-left">Nama Alternatif</th>
                        <th class="text-center">Perhitungan</th>
                        <th class="text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranks as $rank)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $rank['alternatif']->nama_alternatif }}</td>
                        <td class="text-center">
                            @php
                            $formulaParts = [];
                            foreach($kriterias as $kriteria) {
                            $bobot = $kriteria->bobot;
                            $norm = round($normalizedMatrix[$rank['alternatif']->id_alternatif][$kriteria->id_kriteria] ?? 0, 4);
                            $formulaParts[] = "($bobot x $norm)";
                            }
                            echo "SUM " . implode(" + ", $formulaParts);
                            @endphp
                        </td>
                        <td class="text-center font-weight-bold">{{ round($rank['nilai_akhir'], 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection