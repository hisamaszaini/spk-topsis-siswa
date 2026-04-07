@extends('layouts.auth')

@section('content')
<!-- Outer Row -->
<div class="row align-items-center justify-content-center" style="min-height: 80vh;">

    <div class="col-lg-6 text-white text-justify">
        <h1 class="h3 font-weight-bold mb-4">Sistem Pendukung Keputusan Metode TOPSIS</h1>
        <p class="mb-4">Sistem Pendukung Keputusan (SPK) merupakan suatu sistem informasi spesifik yang ditujukan untuk membantu manajemen dalam mengambil keputusan yang berkaitan dengan persoalan yang bersifat semi terstruktur.</p>
        <p>Technique for Order of Preference by Similarity to Ideal Solution (TOPSIS) adalah salah satu metode pengambilan keputusan multi-kriteria yang didasarkan pada konsep bahwa alternatif yang dipilih harus memiliki jarak terpendek dari solusi ideal positif dan jarak terjauh dari solusi ideal negatif.</p>
    </div>

    <div class="col-lg-5 offset-lg-1">
        <div class="card border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Login Account</h1>
                        @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('login.post') }}" class="user">
                        @csrf
                        <div class="form-group">
                            <input type="email" name="email" class="form-control form-control-user"
                                id="exampleInputEmail" aria-describedby="emailHelp"
                                placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user"
                                id="exampleInputPassword" placeholder="Password">
                        </div>
                        <button type="submit" value="Login" class="btn btn-primary btn-user btn-block">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection