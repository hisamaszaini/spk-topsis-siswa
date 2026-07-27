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

    public function index(Request $request)
    {
        $listLomba = TopsisService::getListLomba();
        $activeLomba = $request->query('lomba', array_key_first($listLomba));

        if (!array_key_exists($activeLomba, $listLomba)) {
            $activeLomba = array_key_first($listLomba);
        }

        $data = $this->topsis->calculate($activeLomba);

        if (isset($data['error'])) {
            return redirect()->back()->withErrors($data['error']);
        }

        $this->topsis->saveResults();

        $data['listLomba'] = $listLomba;
        $data['activeLomba'] = $activeLomba;

        return view('hitung.index', $data);
    }
}
