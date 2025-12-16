<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::with('penilaian')->get();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return redirect()->back()->withErrors('Data Kriteria atau Alternatif masih kosong.');
        }

        // 1. Min/Max for Normalization
        $minMax = [];
        foreach ($kriterias as $kriteria) {
            if ($kriteria->jenis == 'Benefit') {
                $minMax[$kriteria->id_kriteria] = Penilaian::where('id_kriteria', $kriteria->id_kriteria)->max('nilai');
            } else {
                $minMax[$kriteria->id_kriteria] = Penilaian::where('id_kriteria', $kriteria->id_kriteria)->min('nilai');
            }
        }

        // 2. Ranking Calculation
        $ranks = [];
        foreach ($alternatifs as $alternatif) {
            $total = 0;
            foreach ($kriterias as $kriteria) {
                // Get raw value
                $nilaiQuery = $alternatif->penilaian->where('id_kriteria', $kriteria->id_kriteria)->first();
                $nilai = $nilaiQuery ? $nilaiQuery->nilai : 0;

                // Normalize
                $divisor = $minMax[$kriteria->id_kriteria] ?? 1;
                if ($divisor == 0) $divisor = 1;

                if ($kriteria->jenis == 'Benefit') {
                    $r = $nilai / $divisor;
                } else {
                    $r = ($nilai != 0) ? $divisor / $nilai : 0;
                }

                // Weighted Sum
                $total += $r * $kriteria->bobot;
            }

            $ranks[] = [
                'alternatif' => $alternatif,
                'nilai_akhir' => $total
            ];
        }

        // Sort Descending
        usort($ranks, function ($a, $b) {
            return $b['nilai_akhir'] <=> $a['nilai_akhir'];
        });

        return view('hasil.index', compact('ranks'));
    }
}
