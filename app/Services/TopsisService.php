<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Hasil;
use Illuminate\Support\Facades\DB;

class TopsisService
{
    /**
     * Menghitung nilai TOPSIS untuk setiap alternatif berdasarkan kriteria yang ada.
     * TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)
     * didasarkan pada konsep bahwa alternatif yang dipilih harus memiliki jarak terpendek 
     * dari solusi ideal positif dan jarak terjauh dari solusi ideal negatif.
     *
     * @return array
     */
    public function calculate(): array
    {
        // 1. Ambil Data Kriteria dan Alternatif dari Database
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::with('penilaian')->get();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return [
                'error' => 'Data Kriteria atau Alternatif masih kosong.'
            ];
        }

        // 2. Langkah 1: Membangun Matriks Keputusan (X)
        // Mengubah data penilaian mentah ke dalam format matriks agar mudah dihitung.
        $matrixX = [];
        foreach ($alternatifs as $alternatif) {
            $row = [];
            foreach ($kriterias as $kriteria) {
                $penilaian = $alternatif->penilaian->where('id_kriteria', $kriteria->id_kriteria)->first();
                $row[$kriteria->id_kriteria] = $penilaian ? $penilaian->nilai : 0;
            }
            $matrixX[$alternatif->id_alternatif] = $row;
        }

        // 3. Langkah 2: Matriks Ternormalisasi (R)
        // R_ij = x_ij / sqrt(sum(x_kj^2))
        // Nilai setiap kriteria dibagi dengan akar kuadrat dari jumlah kuadrat semua nilai pada kriteria tersebut.
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

        // 4. Langkah 3: Matriks Ternormalisasi Terbobot (V)
        // V_ij = w_j * R_ij
        // Mengalikan nilai matriks ternormalisasi dengan bobot masing-masing kriteria.
        $matrixV = [];
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                $r = $matrixR[$alternatif->id_alternatif][$kriteria->id_kriteria];
                $w = $kriteria->bobot;
                $matrixV[$alternatif->id_alternatif][$kriteria->id_kriteria] = $r * $w;
            }
        }

        // 5. Langkah 4: Menentukan Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)
        // Mencari nilai terbaik (maksimum untuk benefit, minimum untuk cost) dan terburuk.
        $pis = []; // A+ (Ideal Positif)
        $nis = []; // A- (Ideal Negatif)
        foreach ($kriterias as $kriteria) {
            $columnV = [];
            foreach ($alternatifs as $alternatif) {
                $columnV[] = $matrixV[$alternatif->id_alternatif][$kriteria->id_kriteria];
            }

            if ($kriteria->jenis == 'benefit') { // Jika kriteria menguntungkan
                $pis[$kriteria->id_kriteria] = max($columnV);
                $nis[$kriteria->id_kriteria] = min($columnV);
            } else { // Jika kriteria adalah beban/biaya (cost)
                $pis[$kriteria->id_kriteria] = min($columnV);
                $nis[$kriteria->id_kriteria] = max($columnV);
            }
        }

        // 6. Langkah 5: Menghitung Jarak Solusi (D+ dan D-)
        // D_i+ = sqrt(sum((v_ij - a_j+)^2))
        // Menghitung seberapa jauh posisi alternatif dari nilai ideal terbaik dan ideal terburuk.
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

        // 7. Langkah 6: Nilai Kedekatan Relatif terhadap Solusi Ideal (C_i)
        // C_i = D_i- / (D_i- + D_i+)
        // Skor akhir yang berkisar antara 0 - 1. Semakin mendekati 1, semakin baik alternatif tersebut.
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

        // Mengurutkan hasil berdasarkan skor tertinggi ke terendah
        usort($preference, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Menambahkan nomor urut (ranking)
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
     * Menyimpan atau menyelaraskan hasil perhitungan ke dalam tabel hasil.
     * Fungsi ini akan menghapus data lama dan menggantikannya dengan hasil perhitungan terbaru.
     */
    public function saveResults(): void
    {
        $data = $this->calculate();
        if (isset($data['error'])) return;

        DB::transaction(function () use ($data) {
            // Delete old results to refresh
            Hasil::query()->delete();

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
