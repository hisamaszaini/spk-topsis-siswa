@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h1 class="text-2xl font-bold mb-6">Input Penilaian</h1>

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Alternatif</label>
                <select name="id_alternatif" id="id_alternatif" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">-- Pilih Alternatif --</option>
                    @foreach($alternatifs as $alt)
                    <option value="{{ $alt->id_alternatif }}" {{ (request('alternatif_id') == $alt->id_alternatif) ? 'selected' : '' }}>
                        {{ $alt->nama_alternatif }} ({{ $alt->kode_alternatif }})
                    </option>
                    @endforeach
                </select>
                <!-- 
                     Ideally we should auto-load existing values if an alternative is selected.
                     For simplicity, we assume generic input form. 
                     If 'alternatif_id' is present in request, we could fetch existing scores to prefill.
                     But passing that data to view needs logic in Controller.
                     Let's check if the Controller passed existing data?
                     I didn't pass specific 'existing_penilaian' to view in my previous step.
                     I can improve this by using JavaScript to fetch values or just reloading page?
                     For now let's just allow overwrite.
                -->
            </div>

            <hr class="mb-6">

            <h3 class="text-xl font-bold mb-4">Isi Nilai Kriteria</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($kriterias as $kriteria)
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ $kriteria->nama_kriteria }} ({{ $kriteria->kode_kriteria }})
                    </label>

                    @if($kriteria->sub_kriteria->count() > 0)
                    <select name="nilai[{{ $kriteria->id_kriteria }}]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">-- Pilih Nilai --</option>
                        @foreach($kriteria->sub_kriteria as $sub)
                        <option value="{{ $sub->nilai }}">
                            {{ $sub->nama_sub }} (Nilai: {{ $sub->nilai }})
                        </option>
                        @endforeach
                    </select>
                    @else
                    <input type="number" step="0.01" name="nilai[{{ $kriteria->id_kriteria }}]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan nilai angka" required>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Simpan
                </button>
                <a href="{{ route('penilaian.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- Optional: Simple Script to handle edit prefill if we wanted to get fancy, but let's keep it simple. -->
@endsection