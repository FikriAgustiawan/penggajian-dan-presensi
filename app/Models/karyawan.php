<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = "karyawan";
    protected $primaryKey = "nik";
    protected $guard_name = 'karyawan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jabatan',
        'no_hp',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class, 'nik', 'nik');
    }
}