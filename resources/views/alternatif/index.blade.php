@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-graduate mr-1"></i> Data Siswa (Alternatif)</h1>
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Alternatif</h6>
        <button class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#createModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-center">NISN</th>
                        <th class="text-center">Nama Siswa</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatifs as $alternatif)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $alternatif->nisn }}</td>
                        <td class="text-center">{{ $alternatif->nama_siswa }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btn-edit"
                                data-id="{{ $alternatif->id_alternatif }}"
                                data-nisn="{{ $alternatif->nisn }}"
                                data-nama="{{ $alternatif->nama_siswa }}"
                                data-toggle="modal" data-target="#editModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('alternatif.destroy', $alternatif->id_alternatif) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
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

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Siswa (Alternatif)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('alternatif.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>NISN</label>
                        <input type="text" name="nisn" class="form-control" required placeholder="Contoh: 0012345678">
                    </div>
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama_siswa" class="form-control" required placeholder="Nama Lengkap Siswa">
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
                <h5 class="modal-title" id="editModalLabel">Edit Siswa (Alternatif)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>NISN</label>
                        <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama_siswa" id="edit_nama_siswa" class="form-control" required>
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
        $('#dataTable').DataTable();

        // Handle Edit Modal
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var nisn = $(this).data('nisn');
            var nama = $(this).data('nama');
            var url = "{{ url('alternatif') }}/" + id;

            $('#editForm').attr('action', url);
            $('#edit_nisn').val(nisn);
            $('#edit_nama_siswa').val(nama);
        });
    });
</script>
@endpush