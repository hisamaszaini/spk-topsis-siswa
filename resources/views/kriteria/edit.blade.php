@extends('layouts.app')

@section('content')

@if ($errors->any())
<div class="alert alert-danger border-left-danger" role="alert">
    <ul class="pl-4 my-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-edit"></i> Edit Data Kriteria</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('kriteria.update', $kriterium->id_kriteria) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control" value="{{ old('kode_kriteria', $kriterium->kode_kriteria) }}" placeholder="Contoh: C1" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" value="{{ old('nama_kriteria', $kriterium->nama_kriteria) }}" placeholder="Nama Kriteria" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Bobot Kriteria</label>
                        <input type="number" step="0.01" name="bobot" class="form-control" value="{{ old('bobot', $kriterium->bobot) }}" placeholder="Bobot Kriteria" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Kriteria</label>
                        <select name="jenis" class="form-control" required>
                            <option value="">--Pilih Jenis Kriteria--</option>
                            <option value="benefit" {{ old('jenis', $kriterium->jenis) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                            <option value="cost" {{ old('jenis', $kriterium->jenis) == 'cost' ? 'selected' : '' }}>Cost</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="text-right">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
                <a href="{{ route('kriteria.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection