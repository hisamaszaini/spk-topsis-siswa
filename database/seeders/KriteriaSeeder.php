<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriterias = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Nilai Matematika',
                'bobot' => 0.35,
                'jenis' => 'benefit',
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Nilai IPA',
                'bobot' => 0.25,
                'jenis' => 'benefit',
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Nilai B. Indo',
                'bobot' => 0.20,
                'jenis' => 'benefit',
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Nilai Olga',
                'bobot' => 0.20,
                'jenis' => 'benefit',
            ],
        ];

        $subKriterias = [
            [
                'nama_sub' => 'Kurang',
                'nilai' => 1,
                'nilai_min' => 0,
                'nilai_max' => 69.99,
            ],
            [
                'nama_sub' => 'Cukup',
                'nilai' => 2,
                'nilai_min' => 70,
                'nilai_max' => 79.99,
            ],
            [
                'nama_sub' => 'Baik',
                'nilai' => 3,
                'nilai_min' => 80,
                'nilai_max' => 89.99,
            ],
            [
                'nama_sub' => 'Sangat Baik',
                'nilai' => 4,
                'nilai_min' => 90,
                'nilai_max' => 100,
            ]
        ];

        foreach ($kriterias as $k) {
            $kriteria = Kriteria::create($k);
            foreach ($subKriterias as $sub) {
                $sub['id_kriteria'] = $kriteria->id_kriteria;
                SubKriteria::create($sub);
            }
        }
    }
}
