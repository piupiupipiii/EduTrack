<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NilaiSiswa;

class NilaiSiswaController extends Controller
{
    public function index()
    {
        $data = NilaiSiswa::all();
        return view('nilai.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'mapel' => 'required',
            'kelas'   => 'required',
            'tugas1' => 'nullable|numeric|min:0|max:100',
            'tugas2' => 'nullable|numeric|min:0|max:100',
            'tugas3' => 'nullable|numeric|min:0|max:100',
            'tugas4' => 'nullable|numeric|min:0|max:100',
            'uts'    => 'nullable|numeric|min:0|max:100',
            'uas'    => 'nullable|numeric|min:0|max:100',
        ]);

    $nilaiAkhir = collect([
        $request->tugas1,
        $request->tugas2,
        $request->tugas3,
        $request->tugas4,
        $request->uts,
        $request->uas,
    ])->filter()->avg(); // Ambil rata-rata dari nilai yang diisi

    NilaiSiswa::create([
        'nama' => $request->nama,
        'kelas' => $request->kelas,
        'mapel' => $request->mapel,
        'tugas1' => $request->tugas1,
        'tugas2' => $request->tugas2,
        'tugas3' => $request->tugas3,
        'tugas4' => $request->tugas4,
        'uts'    => $request->uts,
        'uas'    => $request->uas,
        'nilai'  => round($nilaiAkhir, 2),
    ]);

    return redirect()->back()->with('success', 'Data berhasil ditambahkan');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'mapel' => 'required',
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        $item = NilaiSiswa::findOrFail($id);
        $item->update($request->all());

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        NilaiSiswa::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
