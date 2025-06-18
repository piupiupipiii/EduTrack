@extends('layouts.app')

@section('content')
<div class="upload-container">
    <div class="upload-header">
        <h1 class="upload-title">Upload Materi</h1>
        <p class="upload-subtitle">Unggah materi pembelajaran untuk siswa</p>
    </div>

    {{-- VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan saat mengunggah:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PESAN SUKSES --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- INFO FILE TERAKHIR --}}
    @if(session('filename'))
        <div class="alert alert-info">
            <strong>Nama File:</strong> {{ session('filename') }}<br>
            <strong>Format:</strong> {{ session('filetype') }}<br>
            <strong>Ukuran:</strong> {{ number_format(session('filesize') / 1024, 2) }} KB
        </div>
    @endif

    {{-- FORM UPLOAD --}}
    <div class="upload-card">
        <form action="{{ route('materi.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf
            <div class="form-group">
                <label for="judul" class="form-label">Judul Materi</label>
                <input type="text" name="judul" id="judul" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" name="kelas" id="kelas" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="file" class="form-label">File Materi</label>
                <input type="file" name="file" id="file" class="form-input" required>
            </div>

            <button type="submit" class="submit-btn">Upload Materi</button>
        </form>
    </div>

    {{-- RIWAYAT DAN PREVIEW --}}
    @if(isset($riwayatMateri) && count($riwayatMateri))
    <div class="upload-card" style="margin-top:2rem;">
        <h3 style="margin-bottom:1rem;color:#2c3e50;">Riwayat Upload Materi</h3>

        <div style="display: flex; gap: 2rem;">
            {{-- LIST FILE --}}
            <div style="flex: 1;">
                <ul style="list-style:none;padding:0;">
                    @foreach($riwayatMateri as $materi)
                        @php
                            $ext = strtolower(pathinfo($materi->filename, PATHINFO_EXTENSION));
                            $icon = match($ext) {
                                'pdf' => '📄',
                                'jpg', 'jpeg', 'png' => '🖼️',
                                'mp4' => '🎬',
                                default => '📁'
                            };
                        @endphp
                        <li onclick="previewMateri('{{ $materi->url }}', '{{ $ext }}', '{{ addslashes($materi->judul) }}')" 
                            style="margin-bottom:.8rem;padding:.5rem;border-bottom:1px solid #eee;cursor:pointer">
                            <strong>{{ $icon }} {{ $materi->judul }}</strong> ({{ $materi->kelas }})<br>
                            <small>{{ $materi->filename }} • {{ number_format($materi->filesize/1024,2) }} KB</small>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- PREVIEW PANEL --}}
            <div id="preview-panel" style="flex: 2; border-left: 1px solid #ccc; padding-left: 1rem;">
                <p><em>Klik salah satu file untuk melihat preview di sini.</em></p>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- SCRIPT PREVIEW --}}
<script>
function previewMateri(url, ext, title) {
    let html = `<h4 style="color:#2c3e50;">📂 Preview: ${title}</h4>`;
    ext = ext.toLowerCase();

    if (ext === 'pdf') {
        html += `<iframe src="${url}" width="100%" height="500px" style="border:1px solid #ccc;border-radius:8px;"></iframe>`;
    } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
        html += `<img src="${url}" alt="${title}" style="max-width:100%;border:1px solid #ccc;border-radius:8px;">`;
    } else if (ext === 'mp4') {
        html += `<video controls width="100%" style="border:1px solid #ccc;border-radius:8px;">
                    <source src="${url}" type="video/mp4">Browser kamu tidak mendukung video.
                 </video>`;
    } else {
        html += `<p><em>Preview tidak tersedia untuk file .${ext}</em></p>`;
    }

    document.getElementById('preview-panel').innerHTML = html;
}
</script>

<style>
.upload-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.upload-header {
    text-align: center;
    margin-bottom: 2rem;
}
.upload-title {
    font-size: 2rem;
    color: #2c3e50;
}
.upload-subtitle {
    color: #7f8c8d;
}
.upload-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}
.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.upload-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.form-label {
    font-weight: 500;
    color: #2c3e50;
}
.form-input {
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    font-size: 1rem;
}
.form-input:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}
.submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #2196F3;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
}
.submit-btn:hover {
    background-color: #0d8bf2;
}
@media (max-width: 768px) {
    .upload-container {
        padding: 1rem;
    }
    .upload-card {
        padding: 1.5rem;
    }
    .upload-card > div {
        flex-direction: column;
    }
}
</style>
@endsection
