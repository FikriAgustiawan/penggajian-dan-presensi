<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi'; // Menyebutkan nama tabel

    protected $primaryKey = 'id'; // Menyebutkan primary key

    // Menentukan kolom mana yang bisa diisi
    protected $fillable = [
        'nik',
        'tgl_presensi',
        'jam_in',
        'jam_out',
        'foto_in',
        'foto_out',
        'status',
        'lokasi_in',
        'lokasi_out',
        'kode_jam_kerja',
        'kode_izin'
    ];

    // Menentukan tipe data untuk kolom-kolom tertentu
    protected $casts = [
        'tgl_presensi' => 'date',
        'jam_in' => 'time',
        'jam_out' => 'time',
    ];
}
