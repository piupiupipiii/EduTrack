use App\Models\Nilai;

<?php

namespace App\Http\Controllers;

use App\Models\Nilai;  // Pastikan model Nilai sudah ada
use Illuminate\Http\Request;

class NilaiSiswaController extends Controller
{
    // Menampilkan daftar nilai siswa
    public function index()
    {
        $nilaiSiswa = Nilai::all();  // Ambil semua nilai siswa
        return view('nilai.index', compact('nilaiSiswa'));
    }

    // Menampilkan form untuk menambah nilai siswa
    public function create()
    {
        return view('nilai.create');
    }

    // Menyimpan data nilai siswa
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',  // Misalnya Siswa sudah ada di database
            'nilai' => 'required|numeric',
            'subject' => 'required|string',  // Validasi mata pelajaran
        ]);

        Nilai::create($request->all());
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit nilai siswa
    public function edit(Nilai $nilai)
    {
        return view('nilai.edit', compact('nilai'));
    }

    // Memperbarui data nilai siswa
    public function update(Request $request, Nilai $nilai)
    {
        $request->validate([
            'nilai' => 'required|numeric',
            'subject' => 'required|string',  // Validasi mata pelajaran
        ]);

        $nilai->update($request->all());
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui!');
    }

    // Menghapus nilai siswa
    public function destroy(Nilai $nilai)
    {
        $nilai->delete();
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus!');
    }
}
