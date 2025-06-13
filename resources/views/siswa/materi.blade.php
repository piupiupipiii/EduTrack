@extends('layouts.app')

@section('content')
<div class="upload-container">
    <div class="upload-header">
        <h1 class="upload-title">Upload Materi</h1>
        <p class="upload-subtitle">Unggah materi pembelajaran untuk siswa</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="upload-card">
        <form action="{{ route('materi.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf
            
            <div class="form-group">
                <label for="judul" class="form-label">Judul Materi</label>
                <input type="text" name="judul" id="judul" class="form-input" placeholder="Masukkan judul materi" required>
            </div>

            <div class="form-group">
                <label for="file" class="form-label">File Materi</label>
                <div class="file-upload">
                    <input type="file" name="file" id="file" class="file-input" required>
                    <label for="file" class="file-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <span>Pilih file atau drag & drop</span>
                        <p class="file-hint">Format yang didukung: PDF, DOCX, PPTX, ZIP, Gambar, Video</p>
                    </label>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                </svg>
                Upload Materi
            </button>
        </form>
    </div>
</div>

<style>
    /* Base Styles */
    .upload-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header Styles */
    .upload-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .upload-title {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .upload-subtitle {
        color: #7f8c8d;
        font-size: 1rem;
    }

    /* Alert Styles */
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* Card Styles */
    .upload-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    /* Form Styles */
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
        font-size: 0.95rem;
    }

    .form-input {
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
    }

    /* File Upload Styles */
    .file-upload {
        position: relative;
    }

    .file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    .file-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed #ddd;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-label:hover {
        border-color: #2196F3;
        background-color: #f0f7ff;
    }

    .file-label svg {
        width: 2.5rem;
        height: 2.5rem;
        color: #7f8c8d;
        margin-bottom: 1rem;
    }

    .file-label span {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .file-hint {
        color: #7f8c8d;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    /* Button Styles */
    .submit-btn {
        display: flex;
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

    .submit-btn svg {
        width: 1rem;
        height: 1rem;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .upload-container {
            padding: 1rem;
        }
        
        .upload-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection