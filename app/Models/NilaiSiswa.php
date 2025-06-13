<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_siswa';

    protected $fillable = [
        'nama', 'mapel', 'kelas', 'tugas1', 'tugas2', 'tugas3', 'tugas4', 'uts', 'uas'
    ];
    

}
