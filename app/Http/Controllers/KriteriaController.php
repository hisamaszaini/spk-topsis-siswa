<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('kriteria.index', compact('kriterias'));
    }

    public function create()
    {
        return view('kriteria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|string|max:10|unique:tabel_kriteria',
            'nama_kriteria' => 'required|string|max:100',
            'bobot' => 'required|numeric',
            'jenis' => 'required|in:benefit,cost',
        ]);

        Kriteria::create($request->all());

        return redirect()->route('kriteria.index')->with('success', 'Kriteria created successfully.');
    }

    public function edit(Kriteria $kriterium)
    {
        return view('kriteria.edit', compact('kriterium'));
    }

    public function update(Request $request, Kriteria $kriterium)
    {
        $request->validate([
            'kode_kriteria' => 'required|string|max:10|unique:tabel_kriteria,kode_kriteria,' . $kriterium->id_kriteria . ',id_kriteria',
            'nama_kriteria' => 'required|string|max:100',
            'bobot' => 'required|numeric',
            'jenis' => 'required|in:benefit,cost',
        ]);

        $kriterium->update($request->all());

        return redirect()->route('kriteria.index')->with('success', 'Kriteria updated successfully.');
    }

    public function destroy(Kriteria $kriterium)
    {
        try {
            $kriterium->delete();
            return redirect()->route('kriteria.index')->with('success', 'Kriteria deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('kriteria.index')->with('error', 'Data tidak dapat dihapus karena berelasi dengan data lain (Sub Kriteria/Penilaian).');
            }
            return redirect()->route('kriteria.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
