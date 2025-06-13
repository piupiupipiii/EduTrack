@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="page-title">Manajemen Nilai Siswa</h1>

    <div class="search-section">
        <h2>Cari Data Siswa</h2>
        <div class="search-box">
            <input type="text" placeholder="Cari berdasarkan nama, kelas, atau mata pelajaran">
            <button type="button">Cari</button>
        </div>
    </div>

    <hr class="divider">

    @if(session('success'))
      <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="input-section">
        <h2>Input Data Disini</h2>
        <form class="grade-form" action="{{ route('nilai.store') }}" method="POST">
            @csrf
            <div class="basic-info">
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama" placeholder="Nama Siswa" required>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" placeholder="Kelas" required>
                </div>
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mapel" placeholder="Mata Pelajaran" required>
                </div>
            </div>
            <div class="score-inputs">
                <div class="form-group">
                    <label>Tugas 1</label>
                    <input type="number" name="tugas1" placeholder="Nilai" min="0" max="100">
                </div>
                <div class="form-group">
                    <label>Tugas 2</label>
                    <input type="number" name="tugas2" placeholder="Nilai" min="0" max="100">
                </div>
                <div class="form-group">
                    <label>Tugas 3</label>
                    <input type="number" name="tugas3" placeholder="Nilai" min="0" max="100">
                </div>
                <div class="form-group">
                    <label>Tugas 4</label>
                    <input type="number" name="tugas4" placeholder="Nilai" min="0" max="100">
                </div>
                <div class="form-group">
                    <label>UTS</label>
                    <input type="number" name="uts" placeholder="Nilai" min="0" max="100">
                </div>
                <div class="form-group">
                    <label>UAS</label>
                    <input type="number" name="uas" placeholder="Nilai" min="0" max="100">
                </div>
            </div>
            <button type="submit" class="submit-btn">
                <span>+</span> Tambah Nilai
            </button>
        </form>
    </div>

    <div class="table-container">
        <table class="grade-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Tugas 1</th>
                    <th>Tugas 2</th>
                    <th>Tugas 3</th>
                    <th>Tugas 4</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kelas }}</td>
                        <td>{{ $item->mapel }}</td>
                        <td>{{ $item->tugas1 }}</td>
                        <td>{{ $item->tugas2 }}</td>
                        <td>{{ $item->tugas3 }}</td>
                        <td>{{ $item->tugas4 }}</td>
                        <td>{{ $item->uts }}</td>
                        <td>{{ $item->uas }}</td>
                        <td>{{ round(($item->tugas1 + $item->tugas2 + $item->tugas3 + $item->tugas4 + $item->uts + $item->uas)/6, 2) }}</td>
                        <td class="action-cell">
                            <div class="action-buttons">
                                <a href="{{ route('nilai.edit', $item->id) }}" class="edit-btn">Edit</a>
                                <form action="{{ route('nilai.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Base Styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-title {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 30px;
        font-size: 2rem;
    }

    /* Search Section */
    .search-section {
        margin-bottom: 30px;
    }

    .search-section h2 {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 10px;
    }

    .search-box {
        display: flex;
        gap: 10px;
    }

    .search-box input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .search-box button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Divider */
    .divider {
        border: 0;
        height: 1px;
        background-color: #ddd;
        margin: 20px 0;
    }

    /* Input Section */
    .input-section h2 {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 15px;
    }

    .grade-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .basic-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .score-inputs {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-size: 14px;
        color: #555;
    }

    .form-group input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .submit-btn {
        padding: 10px 20px;
        background-color: #2196F3;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        width: fit-content;
    }

    .submit-btn span {
        font-size: 1.2rem;
    }

    /* Table Styles */
    .table-container {
        margin-top: 30px;
        overflow-x: auto;
    }

    .grade-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .grade-table th {
        background-color: #f5f5f5;
        padding: 12px 8px;
        text-align: center;
        border-bottom: 2px solid #ddd;
    }

    .grade-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    .grade-table tr:hover {
        background-color: #f9f9f9;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .edit-btn, .delete-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 13px;
    }

    .edit-btn {
        background-color: #FFC107;
        color: #000;
    }

    .delete-btn {
        background-color: #F44336;
        color: white;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .basic-info, .score-inputs {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .grade-table {
            font-size: 12px;
        }
        
        .grade-table th, .grade-table td {
            padding: 8px 5px;
        }
    }

    @media (max-width: 480px) {
        .basic-info, .score-inputs {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection