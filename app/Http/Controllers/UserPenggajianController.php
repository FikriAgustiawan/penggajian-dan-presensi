<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Karyawan;

class UserPenggajianController extends Controller
{
    // Halaman utama penggajian
    public function index(Request $request)
    {
        $namabulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $user = Auth::user();

        if (!$user || !$user->nik) {
            return redirect()->back()->with('error', 'Data pengguna tidak valid atau NIK tidak ditemukan!');
        }

        return view('penggajian.user_index', compact('namabulan', 'user'));
    }

    // Cetak slip gaji dalam PDF
    public function cetakSlip(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $user = Auth::user();

        if (!$user || !$user->nik) {
            return redirect()->back()->with('error', 'Data pengguna tidak valid atau NIK tidak ditemukan!');
        }

        if (!$bulan || !$tahun) {
            return redirect()->back()->with('error', 'Bulan dan Tahun harus dipilih!');
        }

        // Ambil data karyawan dan departemen
        $karyawan = Karyawan::join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->select('karyawan.*', 'departemen.nama_dept as departemen')
            ->where('nik', $user->nik)
            ->first();

        if (!$karyawan) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan!');
        }

        // Ambil konfigurasi gaji harian
        $gaji_harian = DB::table('konfigurasi_gaji')->value('gaji_perhari') ?? 100000;

        // Ambil data presensi
        $rekap_presensi = DB::table('presensi')
            ->where('nik', $user->nik)
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->where('status', 'h') // Hanya status hadir
            ->get();

        $jumlah_hadir = $rekap_presensi->count();
        $total_denda = 0;

        foreach ($rekap_presensi as $p) {
            $jamTerlambat = $this->hitungJamTerlambat('08:00:00', $p->jam_in ?? '00:00:00');
            $denda = $this->hitungDenda($jamTerlambat);
            $total_denda += $denda;
        }

        // Hitung total gaji
        $total_gaji = ($jumlah_hadir * $gaji_harian) - $total_denda;

        // Data yang akan dikirim ke view
        $data = compact(
            'karyawan',
            'bulan',
            'tahun',
            'gaji_harian',
            'jumlah_hadir',
            'total_denda',
            'total_gaji'
        );

        // Jika user ingin langsung mendownload PDF
       if ($request->input('download') === 'pdf') {
    $pdf = Pdf::loadView('penggajian.user_slip', $data);
    return $pdf->download('slip_gaji_' . $karyawan->nik . '_' . $bulan . '_' . $tahun . '.pdf');
}


        // Jika hanya ingin melihat slip gaji di halaman
        return view('penggajian.user_slip', $data);
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
}
