@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-edit mr-1"></i> Data Penilaian Siswa</h1>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Penilaian</h6>
        <button class="btn btn-sm btn-success shadow-sm btn-create" data-toggle="modal" data-target="#penilaianModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-left">Alternatif</th>
                        @foreach($kriterias as $kriteria)
                        <th class="text-center">{{ $kriteria->nama_kriteria }}</th>
                        @endforeach
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-weight-bold">{{ $alternatif->nama_siswa }}</td>
                        @foreach($kriterias as $kriteria)
                        @php
                        $nilai = $alternatif->penilaian->where('id_kriteria', $kriteria->id_kriteria)->first();
                        $nilaiValue = $nilai ? $nilai->nilai : 0;
                        @endphp
                        <td class="text-center font-weight-bold">
                            {{ $nilaiValue }}
                        </td>
                        @endforeach
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btn-edit"
                                data-id="{{ $alternatif->id_alternatif }}"
                                data-nama="{{ $alternatif->nama_siswa }}"
                                data-scores="{{ json_encode($alternatif->penilaian->pluck('nilai', 'id_kriteria')) }}"
                                data-toggle="modal" data-target="#penilaianModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('penilaian.destroy', $alternatif->id_alternatif) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus semua penilaian untuk alternatif ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="penilaianModal" tabindex="-1" role="dialog" aria-labelledby="penilaianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penilaianModalLabel">Input Penilaian Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('penilaian.store') }}" method="POST" id="penilaianForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama_siswa" id="nama_siswa" class="form-control" required placeholder="Masukkan Nama Siswa">
                        <input type="hidden" name="id_alternatif" id="id_alternatif">
                    </div>
                    <hr>
                    @foreach($kriterias as $kriteria)
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{ $kriteria->nama_kriteria }}</label>
                        <div class="col-sm-8">
                            <input type="number" step="any" name="nilai[{{ $kriteria->id_kriteria }}]" class="form-control score-input" data-kriteria="{{ $kriteria->id_kriteria }}" required placeholder="Masukkan Nilai">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();

        // Data Alternatif for dropdown (including those without assessment)
        // Note: The loop in blade only iterates $alternatifs which are fetched in controller.
        // If we want ALL alternatifs in dropdown, we should pass all of them.
        // Currently controller passes: $alternatifs = Alternatif::with('penilaian')...->get();
        // So the dropdown has all alternatives.

        $('.btn-create').on('click', function() {
            $('#penilaianModalLabel').text('Tambah Penilaian');
            $('#penilaianForm')[0].reset();
            $('#id_alternatif').val('');
            $('#nama_siswa').val('');
        });

        $('.btn-edit').on('click', function() {
            $('#penilaianModalLabel').text('Edit Penilaian');
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var scores = $(this).data('scores'); // JSON object

            // Set Alternatif ID and Name
            $('#id_alternatif').val(id);
            $('#nama_siswa').val(nama);

            // Reset inputs first
            $('.score-input').val('');

            // Fill inputs
            if (scores) {
                $.each(scores, function(kriteriaId, nilai) {
                    var input = $('.score-input[data-kriteria="' + kriteriaId + '"]');
                    input.val(nilai);
                });
            }
        });
    });
</script>
@endpush