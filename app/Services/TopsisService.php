<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Hasil;
use Illuminate\Support\Facades\DB;

class TopsisService
{
    /**
     * Calculate TOPSIS
     *
     * @return array
     */
    public function calculate(): array
    {
        // 1. Fetch Data
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::with('penilaian')->get();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return [
                'error' => 'Data Kriteria atau Alternatif masih kosong.'
            ];
        }

        // 2. Step 1: Decision Matrix (X)
        $matrixX = [];
        foreach ($alternatifs as $alternatif) {
            $row = [];
            foreach ($kriterias as $kriteria) {
                $penilaian = $alternatif->penilaian->where('id_kriteria', $kriteria->id_kriteria)->first();
                $row[$kriteria->id_kriteria] = $penilaian ? $penilaian->nilai : 0;
            }
            $matrixX[$alternatif->id_alternatif] = $row;
        }

        // 3. Step 2: Normalized Decision Matrix (R)
        // R_ij = x_ij / sqrt(sum(x_kj^2))
        $divider = [];
        foreach ($kriterias as $kriteria) {
            $sumSquared = 0;
            foreach ($alternatifs as $alternatif) {
                $val = $matrixX[$alternatif->id_alternatif][$kriteria->id_kriteria];
                $sumSquared += pow($val, 2);
            }
            $divider[$kriteria->id_kriteria] = sqrt($sumSquared);
        }

        $matrixR = [];
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                $x = $matrixX[$alternatif->id_alternatif][$kriteria->id_kriteria];
                $d = $divider[$kriteria->id_kriteria];
                $matrixR[$alternatif->id_alternatif][$kriteria->id_kriteria] = ($d != 0) ? $x / $d : 0;
            }
        }

        // 4. Step 3: Weighted Normalized Decision Matrix (V)
        // V_ij = w_j * R_ij
        $matrixV = [];
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                $r = $matrixR[$alternatif->id_alternatif][$kriteria->id_kriteria];
                $w = $kriteria->bobot;
                $matrixV[$alternatif->id_alternatif][$kriteria->id_kriteria] = $r * $w;
            }
        }

        // 5. Step 4: Ideal Solution Positive (A+) and Negative (A-)
        $pis = []; // A+
        $nis = []; // A-
        foreach ($kriterias as $kriteria) {
            $columnV = [];
            foreach ($alternatifs as $alternatif) {
                $columnV[] = $matrixV[$alternatif->id_alternatif][$kriteria->id_kriteria];
            }

            if ($kriteria->jenis == 'benefit') { // Lowercase check
                $pis[$kriteria->id_kriteria] = max($columnV);
                $nis[$kriteria->id_kriteria] = min($columnV);
            } else {
                $pis[$kriteria->id_kriteria] = min($columnV);
                $nis[$kriteria->id_kriteria] = max($columnV);
            }
        }

        // 6. Step 5: Separation Measure (D+ and D-)
        // D_i+ = sqrt(sum((v_ij - a_j+)^2))
        $distPIS = [];
        $distNIS = [];
        foreach ($alternatifs as $alternatif) {
            $sumPIS = 0;
            $sumNIS = 0;
            foreach ($kriterias as $kriteria) {
                $v = $matrixV[$alternatif->id_alternatif][$kriteria->id_kriteria];
                $sumPIS += pow($v - $pis[$kriteria->id_kriteria], 2);
                $sumNIS += pow($v - $nis[$kriteria->id_kriteria], 2);
            }
            $distPIS[$alternatif->id_alternatif] = sqrt($sumPIS);
            $distNIS[$alternatif->id_alternatif] = sqrt($sumNIS);
        }

        // 7. Step 6: Relative Closeness to Ideal Solution (C_i)
        // C_i = D_i- / (D_i- + D_i+)
        $preference = [];
        foreach ($alternatifs as $alternatif) {
            $dp = $distPIS[$alternatif->id_alternatif];
            $dn = $distNIS[$alternatif->id_alternatif];
            
            $score = ($dn + $dp != 0) ? $dn / ($dn + $dp) : 0;
            $preference[] = [
                'id_alternatif' => $alternatif->id_alternatif,
                'nama_siswa' => $alternatif->nama_siswa,
                'nisn' => $alternatif->nisn,
                'score' => $score
            ];
        }

        // Sort by score descending
        usort($preference, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Add rank
        foreach ($preference as $key => $item) {
            $preference[$key]['rank'] = $key + 1;
        }

        return [
            'kriterias' => $kriterias,
            'alternatifs' => $alternatifs,
            'matrixX' => $matrixX,
            'matrixR' => $matrixR,
            'matrixV' => $matrixV,
            'pis' => $pis,
            'nis' => $nis,
            'distPIS' => $distPIS,
            'distNIS' => $distNIS,
            'preference' => $preference
        ];
    }

    /**
     * Save/Sync calculation results to tabel_hasil
     */
    public function saveResults(): void
    {
        $data = $this->calculate();
        if (isset($data['error'])) return;

        DB::transaction(function () use ($data) {
            // Delete old results to refresh
            Hasil::truncate();

            foreach ($data['preference'] as $item) {
                Hasil::create([
                    'id_alternatif' => $item['id_alternatif'],
                    'nilai_preferensi' => $item['score'],
                    'peringkat' => $item['rank']
                ]);
            }
        });
    }
}
