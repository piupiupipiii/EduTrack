@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upload Materi</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('materi.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="judul">Judul Materi</label><br>
            <input type="text" name="judul" id="judul" required style="width: 100%; padding: 10px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="file">Pilih File Materi</label><br>
            <input type="file" name="file" id="file" required>
            <p style="font-size: 0.9em; color: #555;">Format yang didukung: PDF, DOCX, PPTX, ZIP, Gambar, Video, dll</p>
        </div>

        <button type="submit" style="padding: 10px 20px; background-color: #2196F3; color: white; border: none; border-radius: 5px;">
            Upload
        </button>
    </form>
</div>
@endsection
