<?php

namespace App\Http\Controllers;
use App\Models\Pengajuanizinsakit;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Model;

class PresensiController extends Controller
{
    public function gethari()
    {
        $hari = date("D");

        switch($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;
            
            case 'Thu':
                $hari_ini = "Kamis";
                break;
                
            case 'Fri':
                $hari_ini = "Jumat";
                break;
                
            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak Di Ketahui";
                break;
        }

        return $hari_ini;
    }

    public function create()
    {
        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensi = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik);
        $cek = $presensi->count();
        $datapresensi = $presensi->first();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)->where('hari', $namahari)->first();
            if($jamkerja == null){
                return view('presensi.notifjadwal');
            } else {
                return view('presensi.create', compact('cek', 'lok_kantor', 'jamkerja'));
            }
        
        if ($datapresensi != null && $datapresensi->status != "h") {
            return view('presensi.notifizin');
        }else if ($jamkerja == null) {
            return view('presensi.notifjadwal');
        }else{
            return view('presensi.create', compact ('cek', 'lok_kantor', 'jamkerja', 'hariini'));
        }
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $status_location = Auth::guard('karyawan')->user()->status_location;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        //Cek Jam Kerja Karyawan
        $namahari = $this->gethari();
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)->where('hari', $namahari)->first();

        $presensi = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik);
        $cek = $presensi->count();
        $datapresensi = $presensi->first();

        if($cek > 0){
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        
        if ($status_location == 1 && $radius > $lok_kantor->radius) {
            echo "error|Maaf Anda Berada Diluar Radius Absen, Jarak Anda ".$radius." Meter Dari Kantor|radius";
        } else {
            if ($cek > 0) {
                if ($jam < $jamkerja->jam_pulang){
                    echo "error|Maaf Belum Waktunya Pulang|out";
                } else if (!empty($datapresensi->jam_out)){
                    echo "error|Anda Sudah Melakukan Absen Pulang Sebelumnya|out";
                } else {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if ($update) {
                        echo "success|Terimakasih, Hati-Hati Di Jalan|out";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen|out";
                    }
                }
                
            } else {
                if ($jam < $jamkerja->awal_jam_masuk) {
                    echo "error|Maaf Belum Waktunya Melakukan Presensi|in";
                } else if ($jam > $jamkerja->akhir_jam_masuk) {
                    echo "error|Maaf Waktu Untuk Presensi Sudah Habis|in";
                } else {
                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h'
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terimakasih, Selamat Bekerja|in";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen|in";
                    }
                }
            }
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
{
    // Pastikan input bertipe float
    $lat1 = (float)$lat1;
    $lon1 = (float)$lon1;
    $lat2 = (float)$lat2;
    $lon2 = (float)$lon2;

    // Periksa apakah nilai latitude dan longitude berada dalam rentang yang valid
    if ($lat1 < -90 || $lat1 > 90 || $lat2 < -90 || $lat2 > 90 || $lon1 < -180 || $lon1 > 180 || $lon2 < -180 || $lon2 > 180) {
        return response()->json(['error' => 'Koordinat tidak valid'], 400);
    }

    // Hitung selisih longitude
    $theta = $lon1 - $lon2;

    // Hitung jarak dalam mil menggunakan rumus Haversine
    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + 
             (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;  // Konversi ke mil

    // Konversi jarak ke satuan lain
    $kilometers = $miles * 1.609344;  // Mil ke Kilometer
    $meters = $kilometers * 1000;  // Kilometer ke Meter

    // Kembalikan jarak dalam meter
    return compact('meters');
}


    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik',$nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request){
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik',$nik)->first();
        $request->validate([
            'foto' => 'image|mimes:png,jpg|max:2000'
        ]);
        if($request->hasFile('foto')){
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }
        if(empty($request->password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];

        }

        $update = DB::table('karyawan')->where('nik',$nik)->update($data);
        if($update){
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath,$foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function histori()
    {
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('presensi.histori',compact('namabulan'));
    }

    public function gethistori(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
        ->select('presensi.*', 'keterangan', 'jam_kerja.*', 'doc_sid', 'nama_cuti')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->leftJoin('pengajuan_izin','presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
        ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
        ->where('presensi.nik',$nik)
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->orderBy('tgl_presensi')
        ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    public function izin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        if(!empty($request->bulan) && !empty($request->tahun)){
            $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti','pengajuan_izin.kode_cuti','=','master_cuti.kode_cuti')
            ->orderBy('tgl_izin_dari','desc')
            ->where('nik',$nik)
            ->whereRaw('MONTH(tgl_izin_dari)="'.$request->bulan.'"')
            ->whereRaw('YEAR(tgl_izin_dari)="'.$request->tahun.'"')
            ->get();
        } else {
            $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti','pengajuan_izin.kode_cuti','=','master_cuti.kode_cuti')
            ->orderBy('tgl_izin_dari','desc')
            ->where('nik',$nik)->limit(5)->orderBy('tgl_izin_dari', 'desc')
            ->get();
        }
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('presensi.izin', compact('dataizin','namabulan'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if($simpan){
            return redirect('/presensi/izin')->with(['success'=>'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error'=>'Data Gagal Disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $user = User::find(Auth::guard('user')->user()->id);

        $tanggal = $request->tanggal;

        $query = Karyawan::query();
        $query->selectRaw('karyawan.nik, nama_lengkap, karyawan.kode_dept,
        datapresensi.id, jam_in, jam_out, foto_in, foto_out, lokasi_in, lokasi_out, datapresensi.status, jam_masuk, nama_jam_kerja, jam_pulang, keterangan'
        );
        $query->leftJoin(
            DB::raw("(
                SELECT
                presensi.nik, presensi.id, jam_in, jam_out, foto_in, foto_out, lokasi_in, lokasi_out, presensi.status, jam_masuk, nama_jam_kerja, jam_pulang, keterangan
                FROM presensi
                LEFT JOIN jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
                LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
                WHERE tgl_presensi = '$tanggal'
            ) datapresensi"), 
            function ($join){
                $join->on('karyawan.nik', '=', 'datapresensi.nik');
            }
        );

        if ($user->hasRole('admin departemen')){
            $query->where('kode_dept', $kode_dept);
        }
        $query->orderBy('nama_lengkap');
        $presensi = $query->get();

        // $presensi = DB::table('presensi')
        // ->select('presensi.*','nama_lengkap','karyawan.kode_dept', 'jam_masuk', 'nama_jam_kerja', 'jam_masuk', 'jam_pulang', 'keterangan')
        // ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        // ->leftjoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
        // ->join('karyawan','presensi.nik','=','karyawan.nik')
        // ->join('departemen','karyawan.kode_dept','=','departemen.kode_dept')
        // ->where('tgl_presensi',$tanggal)
        // ->get();

        return view('presensi.getpresensi', compact('presensi', 'tanggal'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
        ->join('karyawan','presensi.nik','=','karyawan.nik')
        ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
        ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
        ->first();

        $presensi = DB::table('presensi')
        ->select('presensi.*','keterangan','jam_kerja.*')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
        ->where('presensi.nik', $nik)
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->orderBy('tgl_presensi')
        ->get();

        if (isset($_POST['exportexcel'])){
            $time = date("d-m-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
        }

        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $departemen = DB::table('departemen')->get();
        return view('presensi.rekap', compact('namabulan', 'departemen'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_dept = $request->kode_dept;
        $dari = $tahun."-".$bulan."-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        
        $select_date = "";
        $field_date = "";
        $i = 1;
        while(strtotime($dari) <= strtotime($sampai)) {
            $rangetanggal[]= $dari;

            $select_date .= "MAX(IF(tgl_presensi = '$dari',
            CONCAT(
            IFNULL(jam_in,'NA'),'|',
            IFNULL(jam_out,'NA'),'|',
            IFNULL(presensi.status,'NA'),'|',
            IFNULL(nama_jam_kerja,'NA'),'|',
            IFNULL(jam_masuk,'NA'),'|',
            IFNULL(jam_pulang,'NA'),'|',
            IFNULL(presensi.kode_izin,'NA'),'|',
            IFNULL(keterangan,'NA'),'|',
            IFNULL(total_jam,'NA'),'|',
            IFNULL(awal_jam_istirahat,'NA'),'|',
            IFNULL(akhir_jam_istirahat,'NA'),'|'
            ),NULL)) as tgl_" . $i . ",";

            $field_date .= "tgl_" . $i . ",";
            $i++;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }

        $jmlhari = count($rangetanggal);
        $lastrange = $jmlhari - 1;
        $sampai = $rangetanggal[$lastrange];
        if($jmlhari==30){
            array_push($rangetanggal, NULL);
        }else if($jmlhari==29){
            array_push($rangetanggal,NULL,NULL);
        }else if($jmlhari==28){
            array_push($rangetanggal,NULL,NULL,NULL);
        }
        $query = Karyawan::query();
        $query->selectRaw(
            "$field_date karyawan.nik, nama_lengkap, jabatan"
        );

        $query->leftJoin(
            DB::raw("(
                SELECT
                $select_date
                presensi.nik
                FROM presensi
                LEFT JOIN jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
                LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
                WHERE tgl_presensi BETWEEN '$rangetanggal[0]' AND '$sampai'
                GROUP BY nik
            ) as presensi_data"), 'karyawan.nik', '=', 'presensi_data.nik'
        );

        if(!empty($kode_dept)) {
            $query->where('kode_dept', $kode_dept);
        }
        $query->orderBy('nama_lengkap');
        $rekap = $query->get();

        if (isset($_POST['exportexcel'])){
            $time = date("d-m-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }

        $data = ['bulan', 'tahun', 'namabulan', 'rekap', 'rangetanggal', 'jmlhari'];

        if($request->jenis_laporan == 1){
            return view('presensi.cetakrekap', compact($data));
        } else if ($request->jenis_laporan == 2){
            return view('presensi.cetakrekap_detail', compact($data));
        }
    }

    public function izinsakit(Request $request)
    {
        $query = Pengajuanizinsakit::query();
        $query->select('kode_izin','tgl_izin_dari','tgl_izin_sampai','pengajuan_izin.nik','nama_lengkap','jabatan','status','status_approved','keterangan', 'doc_sid');
        $query->join('karyawan','pengajuan_izin.nik','=','karyawan.nik');
        if(!empty($request->dari) && !empty($request->sampai)){
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }

        if(!empty($request->nik)){
            $query->where('pengajuan_izin.nik',$request->nik);
        }

        if(!empty($request->nama_lengkap)){
            $query->where('nama_lengkap','like','%'.$request->nama_lengkap.'%');
        }

        if($request->status_approved != ""){
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin_dari','desc');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $kode_izin = $request->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin',$kode_izin)->first();
        $nik = $dataizin->nik;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;
        DB::beginTransaction();
        try {
            if($status_approved==1){
                while(strtotime($tgl_dari) <= strtotime($tgl_sampai)){
                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d",strtotime("+1 days",strtotime($tgl_dari)));
                }
            }
            
            DB::table('pengajuan_izin')->where('kode_izin',$kode_izin)->update([
                'status_approved' => $status_approved
            ]);
            DB::commit();
            return Redirect::back()->with(['success'=>'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }

        // $update = DB::table('pengajuan_izin')->where('id',$kode_izin)->update([
        //    'status_approved' => $status_approved
        // ]);
        // if($update){
        //    return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        // } else {
        //    return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        // }
    }

    public function batalkanizinsakit($kode_izin)
    {
        DB::beginTransaction();
        try {
            DB::table('pengajuan_izin')->where('kode_izin',$kode_izin)->update([
                'status_approved' => 0
            ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success'=>'Data Berhasil Dibatalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning'=>'Data Gagal Dibatalkan']);
        }

        $update = DB::table('pengajuan_izin')->where('id',$id)->update([
            'status_approved' => 0
        ]);
        if($update){
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

    public function showact($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin)
    {
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $doc_sid = $cekdataizin->doc_sid;

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if ($doc_sid != null){
                Storage::delete('/public/uploads/sid/'.$doc_sid);
            }
            return redirect('/presensi/izin')->with(['success'=>'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error'=>'Data Gagal Dihapus']);
        }
    }

    public function koreksipresensi(Request $request)
    {
        $nik = $request->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $tanggal)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();

        return view('presensi.koreksipresensi', compact('karyawan', 'tanggal', 'jamkerja', 'presensi'));
    }

    public function storekoreksipresensi(Request $request)
    {
        $status = $request->status;
        $nik = $request->nik;
        $tanggal = $request->tanggal;
        $jam_in = $status == "a" ? NULL : $request->jam_in;
        $jam_out = $status == "a" ? NULL : $request->jam_out;
        $kode_jam_kerja = $status == "a" ? NULL : $request->kode_jam_kerja;

        try {
            $cekpresensi = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $tanggal)->count();
            if($cekpresensi > 0){
                DB::table('presensi')
                ->where('nik', $nik)
                ->where('tgl_presensi', $tanggal)
                ->update([
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                    'kode_jam_kerja' => $kode_jam_kerja,
                    'status' => $status
                ]);
            } else {
                DB::table('presensi')->insert([
                    'nik' => $nik,
                    'tgl_presensi' => $tanggal,
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                    'kode_jam_kerja' => $kode_jam_kerja,
                    'status' => $status
                ]);
            }

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }
}
