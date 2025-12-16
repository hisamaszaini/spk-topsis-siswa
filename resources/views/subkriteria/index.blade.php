@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cubes mr-1"></i> Data Sub Kriteria</h1>
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

@foreach($kriterias as $kriteria)
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> {{ $kriteria->nama_kriteria }} ({{ $kriteria->kode_kriteria }})</h6>
        <button class="btn btn-sm btn-success shadow-sm btn-create" data-id="{{ $kriteria->id_kriteria }}" data-toggle="modal" data-target="#createModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-datatable" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-center">Nama Sub Kriteria</th>
                        <th class="text-center">Nilai</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria->sub_kriteria as $sub)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $sub->nama_sub }}</td>
                        <td class="text-center">{{ $sub->nilai }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btn-edit"
                                data-id="{{ $sub->id_sub }}"
                                data-kriteria="{{ $kriteria->id_kriteria }}"
                                data-nama="{{ $sub->nama_sub }}"
                                data-nilai="{{ $sub->nilai }}"
                                data-toggle="modal" data-target="#editModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('sub-kriteria.destroy', $sub->id_sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
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
@endforeach

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Sub Kriteria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('sub-kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_kriteria" id="create_id_kriteria">
                    <div class="form-group">
                        <label>Nama Sub Kriteria</label>
                        <input type="text" name="nama_sub" class="form-control" required placeholder="Contoh: Sangat Baik">
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <input type="number" name="nilai" class="form-control" required placeholder="Contoh: 100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Sub Kriteria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_kriteria" id="edit_id_kriteria">
                    <div class="form-group">
                        <label>Nama Sub Kriteria</label>
                        <input type="text" name="nama_sub" id="edit_nama_sub" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <input type="number" name="nilai" id="edit_nilai" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Init DataTables for all tables with class table-datatable
        $('.table-datatable').DataTable();

        // Handle Create Modal
        $('.btn-create').on('click', function() {
            var id_kriteria = $(this).data('id');
            $('#create_id_kriteria').val(id_kriteria);
        });

        // Handle Edit Modal
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var kriteria = $(this).data('kriteria');
            var nama = $(this).data('nama');
            var nilai = $(this).data('nilai');
            var url = "{{ url('sub-kriteria') }}/" + id;

            $('#editForm').attr('action', url);
            $('#edit_id_kriteria').val(kriteria);
            $('#edit_nama_sub').val(nama);
            $('#edit_nilai').val(nilai);
        });
    });
</script>
@endpush