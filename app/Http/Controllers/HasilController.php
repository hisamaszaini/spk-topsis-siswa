<?php

namespace App\Http\Controllers;

use App\Services\TopsisService;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    protected $topsis;

    public function __construct(TopsisService $topsis)
    {
        $this->topsis = $topsis;
    }

    public function index()
    {
        $listLomba = TopsisService::getListLomba();
        $results = [];

        foreach ($listLomba as $key => $lomba) {
            $calc = $this->topsis->calculate($key);
            if (isset($calc['error'])) {
                return view('hasil.index', ['error' => $calc['error']]);
            }
            $results[$key] = [
                'nama' => $lomba['nama'],
                'preference' => $calc['preference']
            ];
        }

        return view('hasil.index', compact('results', 'listLomba'));
    }
}
