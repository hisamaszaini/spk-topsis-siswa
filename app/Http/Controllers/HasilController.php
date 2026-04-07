<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index()
    {
        // Ambil hasil yang sudah di-save di tabel_hasil, urutkan berdasarkan peringkat
        $ranks = Hasil::with('alternatif')->orderBy('peringkat', 'asc')->get();

        if ($ranks->isEmpty()) {
            return redirect()->route('hitung.index')->withErrors('Silakan lakukan perhitungan terlebih dahulu.');
        }

        return view('hasil.index', compact('ranks'));
    }
}
