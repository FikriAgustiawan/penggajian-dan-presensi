<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiGaji extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_gaji'; // Nama tabel
    protected $fillable = ['gaji_perhari']; // Kolom yang bisa diisi

    public $timestamps = true; // Menggunakan timestamps (created_at, updated_at)
}
