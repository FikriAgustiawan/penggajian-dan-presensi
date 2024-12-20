<?php

namespace App\Http\Controllers;
use App\Models\Setjamkerja;
use App\Models\KonfigurasiGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasikantor()
    {
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request)
    {
        // Validasi input
        $request->validate([
            'lokasi_kantor' => 'required|string',
            'radius' => 'required|numeric',
        ]);

        // Ambil data dari request
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        // Update data di database
        $update = DB::table('konfigurasi_lokasi')->where('id', 1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius,
        ]);

        // Berikan respons berdasarkan hasil update
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function jamkerja()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jamkerja', compact('jam_kerja'));
    }

    public function storejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $status_istirahat = $request->status_istirahat;
        $awal_jam_istirahat = $request->awal_jam_istirahat;
        $akhir_jam_istirahat = $request->akhir_jam_istirahat;
        $jam_pulang = $request->jam_pulang;
        $total_jam = $request->total_jam;

        $data = [
            'kode_jam_kerja' => $kode_jam_kerja,
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'status_istirahat' => $status_istirahat,
            'awal_jam_istirahat' => $awal_jam_istirahat,
            'akhir_jam_istirahat' => $akhir_jam_istirahat,
            'jam_pulang' => $jam_pulang,
            'total_jam' => $total_jam
        ];
        try {
            DB::table('jam_kerja')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function editjamkerja(Request $request) {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.editjamkerja', compact('jamkerja'));
    }

    public function updatejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $status_istirahat = $request->status_istirahat;
        $awal_jam_istirahat = $request->awal_jam_istirahat;
        $akhir_jam_istirahat = $request->akhir_jam_istirahat;
        $jam_pulang = $request->jam_pulang;
        $total_jam = $request->total_jam;

        $data = [
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'status_istirahat' => $status_istirahat,
            'awal_jam_istirahat' => $awal_jam_istirahat,
            'akhir_jam_istirahat' => $akhir_jam_istirahat,
            'jam_pulang' => $jam_pulang,
            'total_jam' => $total_jam,
        ];
        try {
            DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function deletejamkerja($kode_jam_kerja)
    {
        $hapus = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }

    public function setjamkerja($nik)
    {
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cekjamkerja = DB::table('konfigurasi_jamkerja')->where('nik', $nik)->count();
        if($cekjamkerja > 0){
            $setjamkerja = DB::table('konfigurasi_jamkerja')->where('nik', $nik)->get();
            return view('konfigurasi.editsetjamkerja', compact('karyawan', 'jamkerja', 'setjamkerja'));
        }else{
            return view('konfigurasi.setjamkerja', compact('karyawan', 'jamkerja'));
        }
    }

    public function storesetjamkerja(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        for($i=0; $i < count($hari); $i++)
        {
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }

        try {
            Setjamkerja::insert($data);
            return redirect('/karyawan')->with(['success' => 'Jam Kerja Berhasil Di Setting']);
        } catch (\Exception $e) {
            return redirect('/karyawan')->with(['warning' => 'Jam Kerja Gagal Di Setting']);
        }
        
    }

    public function updatesetjamkerja(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        for($i=0; $i < count($hari); $i++)
        {
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }

        DB::beginTransaction();
        try {
            DB::table('konfigurasi_jamkerja')->where('nik', $nik)->delete();
            Setjamkerja::insert($data);
            DB::commit();
            return redirect('/karyawan')->with(['success' => 'Jam Kerja Berhasil Di Setting']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/karyawan')->with(['warning' => 'Jam Kerja Gagal Di Setting']);
        }
        
    }

    public function konfigurasiGaji()
    {
        $konfigurasiGaji = KonfigurasiGaji::first(); // Ambil konfigurasi gaji pertama
        return view('konfigurasi.konfigurasi_gaji', compact('konfigurasiGaji'));
    }

    // Menampilkan form untuk menambah konfigurasi gaji
    public function createKonfigurasiGaji()
    {
        // Cek jika sudah ada konfigurasi gaji
        $konfigurasiGajiExist = KonfigurasiGaji::count();

        if ($konfigurasiGajiExist > 0) {
            return redirect()->route('konfigurasi.gaji')->with('warning', 'Konfigurasi gaji hanya bisa ditambahkan sekali. Anda bisa mengedit data yang ada.');
        }

        return view('konfigurasi.create_konfigurasi_gaji');
    }

    // Menyimpan konfigurasi gaji baru
    public function storeKonfigurasiGaji(Request $request)
    {
        // Validasi input
        $request->validate([
            'gaji_perhari' => 'required|numeric|min:0',
        ]);

        // Cek jika sudah ada konfigurasi gaji
        if (KonfigurasiGaji::count() > 0) {
            return redirect()->route('konfigurasi.gaji')->with('warning', 'Konfigurasi gaji hanya bisa ditambahkan sekali. Anda bisa mengedit data yang ada.');
        }

        // Simpan konfigurasi gaji baru
        KonfigurasiGaji::create([
            'gaji_perhari' => $request->gaji_perhari,
        ]);

        return redirect()->route('konfigurasi.gaji')->with('success', 'Konfigurasi Gaji berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit konfigurasi gaji
    public function editKonfigurasiGaji($id)
    {
        $konfigurasiGaji = KonfigurasiGaji::findOrFail($id);
        return view('konfigurasi.edit_konfigurasi_gaji', compact('konfigurasiGaji'));
    }

    // Mengupdate konfigurasi gaji
    public function updateKonfigurasiGaji(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'gaji_perhari' => 'required|numeric|min:0',
        ]);

        $konfigurasiGaji = KonfigurasiGaji::findOrFail($id);
        $konfigurasiGaji->update([
            'gaji_perhari' => $request->gaji_perhari,
        ]);

        return redirect()->route('konfigurasi.gaji')->with('success', 'Konfigurasi Gaji berhasil diperbarui.');
    }

    // Menghapus konfigurasi gaji
    public function destroyKonfigurasiGaji($id)
    {
        $konfigurasiGaji = KonfigurasiGaji::findOrFail($id);
        $konfigurasiGaji->delete();

        return redirect()->route('konfigurasi.gaji')->with('success', 'Konfigurasi Gaji berhasil dihapus.');
    }

}
