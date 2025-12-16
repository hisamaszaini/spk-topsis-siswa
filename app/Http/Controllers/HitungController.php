<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class HitungController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::with('penilaian')->get();

        // Check if data exists
        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return redirect()->back()->withErrors('Data Kriteria atau Alternatif masih kosong.');
        }

        // 1. Prepare Matrix Decisions (X) and Max/Min values
        $minMax = [];
        foreach ($kriterias as $kriteria) {
            // Get all nilai for this kriteria
            // We assume table 'penilaian' has all scores.
            // Better to match with specific alternatives if data is partial, 
            // but for simplicity query from Penilaian model or filter collection.

            // Using collection method from loaded relationship might specific validity check
            // But getting min/max from DB is faster.
            if ($kriteria->jenis == 'Benefit') {
                $minMax[$kriteria->id_kriteria] = Penilaian::where('id_kriteria', $kriteria->id_kriteria)->max('nilai');
            } else {
                $minMax[$kriteria->id_kriteria] = Penilaian::where('id_kriteria', $kriteria->id_kriteria)->min('nilai');
            }
        }

        // 2. Normalize Matrix (R)
        // We will store this in a temporary array structure
        $normalizedMatrix = [];
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                $nilai_penilaian = $alternatif->penilaian->where('id_kriteria', $kriteria->id_kriteria)->first();
                $nilai = $nilai_penilaian ? $nilai_penilaian->nilai : 0;

                $divisor = $minMax[$kriteria->id_kriteria] ?? 1; // Avoid division by zero
                if ($divisor == 0) $divisor = 1;

                if ($kriteria->jenis == 'Benefit') {
                    // R = x / Max
                    $r = $nilai / $divisor;
                } else {
                    // R = Min / x
                    // If nilai is 0, we have an issue. Assume 0.
                    $r = ($nilai != 0) ? $divisor / $nilai : 0;
                }

                $normalizedMatrix[$alternatif->id_alternatif][$kriteria->id_kriteria] = $r;
            }
        }

        // 3. Calculate Preference Value (V) -> Ranking
        $ranks = [];
        foreach ($alternatifs as $alternatif) {
            $total = 0;
            foreach ($kriterias as $kriteria) {
                $bobot = $kriteria->bobot;
                $r = $normalizedMatrix[$alternatif->id_alternatif][$kriteria->id_kriteria];
                $total += $bobot * $r;
            }
            $ranks[] = [
                'alternatif' => $alternatif,
                'nilai_akhir' => $total
            ];
        }

        // Sort by nilai_akhir Descending
        usort($ranks, function ($a, $b) {
            return $b['nilai_akhir'] <=> $a['nilai_akhir'];
        });

        return view('hitung.index', compact('kriterias', 'alternatifs', 'normalizedMatrix', 'ranks'));
    }
}
