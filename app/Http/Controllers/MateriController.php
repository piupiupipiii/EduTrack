<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        return view('materi.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|max:102400', // max 100MB
        ]);

        $file = $request->file('file');
        $path = $file->store('materi', 'gcs'); // simpan ke bucket GCS
        $url = Storage::disk('gcs')->url($path);

        return redirect()->back()->with('success', 'Materi berhasil diunggah. URL: ' . $url);
    }
}
