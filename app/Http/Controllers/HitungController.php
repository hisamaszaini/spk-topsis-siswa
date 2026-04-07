<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\TopsisService;
use Illuminate\Http\Request;

class HitungController extends Controller
{
    protected $topsis;

    public function __construct(TopsisService $topsis)
    {
        $this->topsis = $topsis;
    }

    public function index()
    {
        $data = $this->topsis->calculate();

        if (isset($data['error'])) {
            return redirect()->back()->withErrors($data['error']);
        }

        // Simpan hasil ke tabel_hasil (opsional: bisa dipindah ke tombol khusus jika data sangat besar)
        $this->topsis->saveResults();

        return view('hitung.index', $data);
    }
}
