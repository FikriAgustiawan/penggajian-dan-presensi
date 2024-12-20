<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin'; // Nama tabel
    protected $fillable = [
        'nik',
        'tgl_izin',
        'status',
        'keterangan',
        'status_approved',
    ];

    // Relasi ke tabel karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
