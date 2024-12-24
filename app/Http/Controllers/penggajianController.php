<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class penggajianController extends Controller
{
    public function index(Request $request)
    {
        // Definisikan nama-nama bulan
        $namabulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Ambil data karyawan dari database
        $karyawan = DB::table('karyawan')->select('nik', 'nama_lengkap')->get();

        // Inisialisasi rekap gaji kosong
        $rekapGaji = [];

        return view('penggajian.index', compact('namabulan', 'karyawan', 'rekapGaji'));
    }

    public function filter(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = $request->nik;

        // Validasi input
        if (empty($bulan) || empty($tahun) || empty($nik)) {
            return redirect()->back()->with('error', 'Semua filter harus diisi!');
        }

        // Ambil gaji harian dari tabel konfigurasi_gaji
        $gajiHarian = DB::table('konfigurasi_gaji')->value('gaji_perhari');
        if (!$gajiHarian) {
            return redirect()->back()->with('error', 'Gaji belum dikonfigurasi!');
        }

        // Ambil data presensi dengan filter
        $rekapPresensi = DB::table('presensi')
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->where('nik', $nik)
            ->where('status', 'h') // Hanya status hadir
            ->get();

        $jumlahHadir = 0;
        $totalDenda = 0;
        $totalJamKerja = 0;

        foreach ($rekapPresensi as $p) {
            $jumlahHadir++;

            // Hitung keterlambatan
            $jamTerlambat = $this->hitungJamTerlambat('08:00:00', $p->jam_in ?? '00:00:00');
            $denda = $this->hitungDenda($jamTerlambat);

            $totalDenda += $denda;

            // Hitung jam kerja
            $jamKerja = $this->hitungJamKerja($p->jam_in ?? '00:00:00', $p->jam_out ?? '00:00:00');
            $totalJamKerja += $jamKerja;
        }

        // Hitung total gaji
        $totalGaji = ($jumlahHadir * $gajiHarian) - $totalDenda;

        // Simpan hasil
        $rekapGaji = [
            [
                'nik' => $nik,
                'nama' => DB::table('karyawan')->where('nik', $nik)->value('nama_lengkap'),
                'jumlah_hadir' => $jumlahHadir,
                'total_denda' => $totalDenda,
                'total_jam_kerja' => $totalJamKerja,
                'gaji_harian' => $gajiHarian,
                'total_gaji' => $totalGaji,
            ],
        ];

        // Definisikan nama-nama bulan
        $namabulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $karyawan = DB::table('karyawan')->select('nik', 'nama_lengkap')->get();

        return view('penggajian.index', compact('namabulan', 'karyawan', 'rekapGaji'));
    }

    private function hitungJamTerlambat($jadwalJamMasuk, $jamPresensi)
    {
        $j1 = strtotime($jadwalJamMasuk);
        $j2 = strtotime($jamPresensi);
        $diffterlambat = $j2 - $j1;

        if ($diffterlambat <= 0) {
            return "00:00"; // Tidak terlambat
        }

        $jamterlambat = floor($diffterlambat / (60 * 60));
        $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

        $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
        $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;

        return $jterlambat . ":" . $mterlambat;
    }

    private function hitungDenda($jamTerlambat)
    {
        $jTerlambat = explode(":", $jamTerlambat);
        $jam = intval($jTerlambat[0]);
        $menit = intval($jTerlambat[1]);

        if ($jam < 1) {
            if ($menit >= 5 && $menit < 10) {
                return 5000;
            } elseif ($menit >= 10 && $menit < 15) {
                return 10000;
            } elseif ($menit >= 15) {
                return 15000;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    private function hitungJamKerja($jamMasuk, $jamKeluar)
    {
        $jMasuk = strtotime($jamMasuk);
        $jKeluar = strtotime($jamKeluar);

        if ($jKeluar <= $jMasuk) {
            return 0; // Tidak ada jam kerja jika jam keluar tidak valid
        }

        $diff = $jKeluar - $jMasuk;
        $jamKerja = floor($diff / (60 * 60));
        $menitKerja = floor(($diff - ($jamKerja * (60 * 60))) / 60);

        return $jamKerja + round($menitKerja / 60, 2); // Hasil dalam desimal
    }

    public function cetakSlip($nik, $bulan, $tahun)
{
    // Ambil data karyawan beserta departemen
    $karyawan = DB::table('karyawan')
        ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
        ->select('karyawan.nik', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'departemen.nama_dept as departemen')
        ->where('karyawan.nik', $nik)
        ->first();

    if (!$karyawan) {
        return redirect()->back()->with('error', 'Data karyawan tidak ditemukan!');
    }

    // Ambil gaji harian
    $gajiHarian = DB::table('konfigurasi_gaji')->value('gaji_perhari');
    if (!$gajiHarian) {
        return redirect()->back()->with('error', 'Gaji belum dikonfigurasi!');
    }

    // Ambil presensi untuk bulan dan tahun yang dipilih
    $rekapPresensi = DB::table('presensi')
        ->whereMonth('tgl_presensi', $bulan)
        ->whereYear('tgl_presensi', $tahun)
        ->where('nik', $nik)
        ->where('status', 'h') // Hanya status hadir
        ->get();

    $jumlahHadir = $rekapPresensi->count();
    $totalDenda = 0;

    foreach ($rekapPresensi as $p) {
        // Hitung keterlambatan
        $jamTerlambat = $this->hitungJamTerlambat('08:00:00', $p->jam_in ?? '00:00:00');
        $denda = $this->hitungDenda($jamTerlambat);

        $totalDenda += $denda;
    }

    // Hitung total gaji
    $totalGaji = ($jumlahHadir * $gajiHarian) - $totalDenda;

    // Data untuk view
    $data = [
        'karyawan' => $karyawan,
        'jumlah_hadir' => $jumlahHadir,
        'gaji_harian' => $gajiHarian,
        'total_denda' => $totalDenda,
        'total_gaji' => $totalGaji,
        'bulan' => $bulan,
        'tahun' => $tahun,
    ];

    return view('penggajian.slip', $data);
}

}
