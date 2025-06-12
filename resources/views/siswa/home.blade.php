@extends('layouts.app')

@section('title', 'Homepage')

@section('content')

<style>
    body {
        background: #f9fafb;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .homepage-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 80vh;
        text-align: center;
        padding: 2rem;
    }

    .homepage-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 2.5rem;
    }

    .action-button {
        background: linear-gradient(to right, #4f46e5, #6366f1);
        color: white;
        padding: 1rem 2rem;
        margin: 0.75rem;
        border: none;
        border-radius: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 280px;
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px rgba(79, 70, 229, 0.3);
    }

    .action-button:active {
        transform: translateY(0);
    }

    .button-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    a {
        text-decoration: none;
        width: 100%;
        display: flex;
        justify-content: center;
    }
</style>

<div class="container homepage-container">
    <div class="homepage-title">Selamat Datang di Homepage</div>

    <div class="button-container">
        <a href="{{ route('nilai.index') }}">
            <button class="action-button">
                <span>🔎</span> Nilai Siswa
            </button>
        </a>

        <a href="{{ route('upload.form') }}">
            <button class="action-button">
                <span>☁️</span> Upload Materi
            </button>
        </a>
    </div>
</div>

@endsection