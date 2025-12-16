<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::with(['penilaian' => function ($query) {
            $query->join('kriteria', 'penilaian.id_kriteria', '=', 'kriteria.id_kriteria')
                ->orderBy('kriteria.id_kriteria'); // Ensure consistent order
        }])->get();
        // Eager load sub_kriteria for dropdowns in Modal
        $kriterias = Kriteria::with('sub_kriteria')->get();

        return view('penilaian.index', compact('alternatifs', 'kriterias'));
    }

    public function create()
    {
        $alternatifs = Alternatif::all();
        $kriterias = Kriteria::with('sub_kriteria')->get();
        return view('penilaian.create', compact('alternatifs', 'kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_alternatif' => 'required|exists:alternatif,id_alternatif',
            'nilai' => 'required|array',
        ]);

        $id_alternatif = $request->id_alternatif;

        // Delete existing assessments for this alternative (fresh start/update)
        Penilaian::where('id_alternatif', $id_alternatif)->delete();

        foreach ($request->nilai as $id_kriteria => $nilai) {
            Penilaian::create([
                'id_alternatif' => $id_alternatif,
                'id_kriteria' => $id_kriteria,
                'nilai' => $nilai,
            ]);
        }

        return redirect()->route('penilaian.index')->with('success', 'Penilaian saved successfully.');
    }

    public function edit(Alternatif $alternatif)
    {
        // Actually we can reuse create view logic or create a dedicated edit view
        // Is 'penilaian' resource route passing 'penilaian' id? Yes.
        // But we want to edit by Alternatif.
        // The resource route 'penilaian.edit' expects a $penilaian object.
        // My design creates multiple Penilaian rows per Alternatif.
        // So standard resource 'edit' might be tricky if I want to edit ALL scores for an alternative at once.
        // Better to have a custom 'edit' route or just handle it in create with pre-filled data?
        // Let's assume for now we use 'create' style form but pre-filled.
        // But the route resource binds to Penilaian model.
        // I will overload or ignore the standard edit for single row and create a custom one?
        // Or simpler: Just use Create to "Upsert".
        // Let's redirect 'edit' to a custom logic if needed, but for now I'll just skip 'edit' row-by-row
        // and provide a button "Edit Scores" on the Index that goes to a page similar to Create but with data.

        // Let's make a route to edit by Alternatif ID.
        // Since I can't easily change resource routes without clean up, I'll add a custom route or logic.
        // For simplicity: specific actions in Create view to load data if requested?
        // Or just `create?alternatif_id=1`.
        return redirect()->route('penilaian.create', ['alternatif_id' => $alternatif->id_alternatif]);
    }

    public function destroy($id_alternatif)
    {
        try {
            Penilaian::where('id_alternatif', $id_alternatif)->delete();
            return redirect()->route('penilaian.index')->with('success', 'Penilaian deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('penilaian.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
