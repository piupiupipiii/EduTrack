<?php
// app/Http/Controllers/MateriController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Materi;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    public function index()
    {
        $riwayatMateri = Materi::latest()->take(10)->get();
        return view('siswa.materi', compact('riwayatMateri'));
    }


    public function upload(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'file' => 'required|file|mimes:pdf,docx,pptx,zip,jpg,jpeg,png,mp4|max:10240'
        ]);

        $kelas = Str::slug($request->kelas, '_');
        $file = $request->file('file');
        $filename = time() . '_' . Str::slug($file->getClientOriginalName(), '_');
        $path = $file->storeAs("public/materi/{$kelas}", $filename);
        $url = asset("storage/materi/{$kelas}/{$filename}");
        
        // Simpan ke database
        Materi::create([
            'judul' => $request->judul,
            'kelas' => $request->kelas,
            'filename' => $filename,
            'filetype' => $file->getClientMimeType(),
            'filesize' => $file->getSize(),
            'url' => $url,
        ]);

        return back()->with([
            'success'  => 'File berhasil diupload!',
            'filename' => $filename,
            'filetype' => $file->getClientMimeType(),
            'filesize' => $file->getSize(),
            'url'      => $url,
        ]);
    }
}