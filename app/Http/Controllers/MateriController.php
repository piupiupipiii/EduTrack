<?php
// app/Http/Controllers/MateriController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        return view('siswa.materi');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $filetype = $file->getClientOriginalExtension();
        $filesize = $file->getSize();

        // Upload ke backend Flask / GCS bisa ditambahkan di sini...
        $path = Storage::disk('gcs')->putFileAs('', $file, $filename);

        // dapatkan URL public (jika visibility = public)
        $url  = Storage::disk('gcs')->url($path);


        return back()->with([
            'success'  => 'File berhasil diupload!',
            'filename' => $filename,
            'filetype' => $file->getClientOriginalExtension(),
            'filesize' => $file->getSize(),
            'url'      => $url,
        ]);
    }

}
