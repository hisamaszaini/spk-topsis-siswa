<?php

namespace App\Http\Controllers;

use App\Models\SubKriteria;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::with('sub_kriteria')->get();
        return view('subkriteria.index', compact('kriterias'));
    }

    public function create()
    {
        $kriterias = Kriteria::all();
        return view('subkriteria.create', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kriteria' => 'required|exists:kriteria,id_kriteria',
            'nama_sub' => 'required|string|max:100',
            'nilai' => 'required|numeric',
        ]);

        SubKriteria::create($request->all());

        return redirect()->route('sub-kriteria.index')->with('success', 'Sub Kriteria created successfully.');
    }

    public function edit(SubKriteria $subKriterium)
    {
        $kriterias = Kriteria::all();
        return view('subkriteria.edit', compact('subKriterium', 'kriterias'));
    }

    public function update(Request $request, SubKriteria $subKriterium)
    {
        $request->validate([
            'id_kriteria' => 'required|exists:kriteria,id_kriteria',
            'nama_sub' => 'required|string|max:100',
            'nilai' => 'required|numeric',
        ]);

        $subKriterium->update($request->all());

        return redirect()->route('sub-kriteria.index')->with('success', 'Sub Kriteria updated successfully.');
    }

    public function destroy(SubKriteria $subKriterium)
    {
        try {
            $subKriterium->delete();
            return redirect()->route('sub-kriteria.index')->with('success', 'Sub Kriteria deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('sub-kriteria.index')->with('error', 'Data tidak dapat dihapus karena sedang digunakan.');
        }
    }
}
