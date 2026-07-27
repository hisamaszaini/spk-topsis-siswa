<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::all();
        return view('alternatif.index', compact('alternatifs'));
    }

    public function create()
    {
        return view('alternatif.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:100',
        ]);

        Alternatif::create($request->all());

        return redirect()->route('alternatif.index')->with('success', 'Alternatif berhasil ditambahkan.');
    }

    public function edit(Alternatif $alternatif)
    {
        return view('alternatif.edit', compact('alternatif'));
    }

    public function update(Request $request, Alternatif $alternatif)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:100',
        ]);

        $alternatif->update($request->all());

        return redirect()->route('alternatif.index')->with('success', 'Alternatif berhasil diperbarui.');
    }

    public function destroy(Alternatif $alternatif)
    {
        try {
            $alternatif->delete();
            return redirect()->route('alternatif.index')->with('success', 'Alternatif berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('alternatif.index')->with('error', 'Data tidak dapat dihapus karena sedang digunakan di tabel lain.');
        }
    }
}
