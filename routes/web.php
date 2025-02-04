<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\UserPenggajianController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

// Route untuk tamu
Route::middleware(['guest:web,karyawan'])->group(function () {
    // Route untuk menampilkan form login (GET)
    Route::get('/', function() {
        return view('auth.login');
    })->name('login');

    // Route untuk memproses login (POST)
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Rute untuk pengguna yang sudah login
Route::middleware(['auth:web,karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/proseslogout', [AuthController::class, 'logout'])->name('logout');

   // Rute untuk presensi
   Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
   Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');

   //Edit Profile
   Route::get('/editprofile', [PresensiController::class, 'editprofile']);
   Route::post('/presensi/{nik}/updateprofile',[PresensiController::class,'updateprofile']);

   //Histori
   Route::get('/presensi/histori',[PresensiController::class, 'histori']);
   Route::post('/gethistori', [PresensiController::class, 'gethistori']);

   //Izin
   Route::get('/presensi/izin', [PresensiController::class, 'izin']);
   Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
   Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
   Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);

   //Izin Absen
   Route::get('/izinabsen', [IzinabsenController::class, 'create'])->name('izinabsen.create');
   Route::post('/izinabsen/store', [IzinabsenController::class, 'store'])->name('izinabsen.store');
   Route::get('/izinabsen/{kode_izin}/edit', [IzinabsenController::class, 'edit']);
   Route::post('/izinabsen/{kode_izin}/update', [IzinabsenController::class, 'update']);

   //Izin Sakit
   Route::get('/izinsakit', [IzinsakitController::class, 'create']);
   Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
   Route::get('/izinsakit/{kode_izin}/edit', [IzinsakitController::class, 'edit']);
   Route::post('/izinsakit/{kode_izin}/update', [IzinsakitController::class, 'update']);

   //Izin Cuti
   Route::get('/izincuti', [IzincutiController::class, 'create']);
   Route::post('/izincuti/store', [IzincutiController::class, 'store']);
   Route::get('/izincuti/{kode_izin}/edit', [IzincutiController::class, 'edit']);
   Route::post('/izincuti/{kode_izin}/update', [IzincutiController::class, 'update']);
   Route::post('/izincuti/getmaxcuti', [IzincutiController::class, 'getmaxcuti']);

   Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
   Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
   
   // Rute untuk penggajian karyawan
   // Rute untuk menampilkan halaman utama penggajian
Route::get('/penggajian-karyawan', [UserPenggajianController::class, 'index'])->name('penggajian.index');

// Rute untuk menampilkan slip gaji (tampilan HTML)
Route::get('/penggajian/slip', [UserPenggajianController::class, 'cetakSlip'])->name('penggajian.slip');

// Rute untuk mengunduh slip gaji dalam format PDF
Route::get('/penggajian/slip/download', [UserPenggajianController::class, 'downloadSlip'])->name('penggajian.slip.download');

});


// Route khusus admin
Route::middleware(['auth:web', 'role:administrator'])->prefix('panel')->group(function () {
    // Dashboard Admin
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    // Data Master Routes
    Route::prefix('master')->group(function () {
        // Karyawan
        Route::get('/karyawan', [KaryawanController::class, 'index']);
        Route::post('/karyawan/store', [KaryawanController::class, 'store']);
        Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
        Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
        Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);
        Route::get('/karyawan/{nik}/resetpassword', [KaryawanController::class, 'resetpassword']);
        Route::get('/karyawan/{nik}/lockandunlocklocation', [KaryawanController::class, 'lockandunlocklocation']);

        // Departemen
        Route::get('/departemen', [DepartemenController::class, 'index']);
        Route::post('/departemen/store', [DepartemenController::class, 'store']);
        Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
        Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
        Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

        // Cuti
        Route::get('/cuti', [CutiController::class, 'index']);
        Route::post('/cuti/store', [CutiController::class, 'store']);
        Route::post('/cuti/edit', [CutiController::class, 'edit']);
        Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
        Route::post('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);
    });

    // Presensi Routes
    Route::prefix('presensi')->group(function () {
        Route::get('/monitoring', [PresensiController::class, 'monitoring']);
        Route::get('/izinsakit', [PresensiController::class, 'izinsakit']);
        Route::get('/laporan', [PresensiController::class, 'laporan']);
        Route::get('/rekap', [PresensiController::class, 'rekap']);
        Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
        Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
        Route::post('/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
        Route::post('/cetakrekap', [PresensiController::class, 'cetakrekap']);
        Route::post('/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
        Route::get('/{kode_izin}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);
        Route::post('/koreksipresensi', [PresensiController::class, 'koreksipresensi']);
        Route::post('/storekoreksipresensi', [PresensiController::class, 'storekoreksipresensi']);
    });

    // Konfigurasi Routes
    Route::prefix('konfigurasi')->group(function () {
        Route::get('/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
        Route::post('/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
        Route::get('/jamkerja', [KonfigurasiController::class, 'jamkerja']);
        Route::post('/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
        Route::post('/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
        Route::post('/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);
        Route::post('/jamkerja/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);
        Route::get('/{nik}/setjamkerja', [KonfigurasiController::class, 'setjamkerja']);
        Route::post('/storesetjamkerja', [KonfigurasiController::class, 'storesetjamkerja']);
        Route::post('/updatesetjamkerja', [KonfigurasiController::class, 'updatesetjamkerja']);
        
        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users/store', [UserController::class, 'store']);
        Route::post('/users/edit', [UserController::class, 'edit']);
        Route::post('/users/{id_user}/update', [UserController::class, 'update']);
        Route::post('/users/{id_user}/delete', [UserController::class, 'delete']);

        // Gaji
        Route::get('/gaji', [KonfigurasiController::class, 'konfigurasiGaji'])->name('konfigurasi.gaji');
        Route::get('/gaji/create', [KonfigurasiController::class, 'createKonfigurasiGaji'])->name('konfigurasi.create_gaji');
        Route::post('/gaji', [KonfigurasiController::class, 'storeKonfigurasiGaji'])->name('konfigurasi.store_gaji');
        Route::get('/gaji/{id}/edit', [KonfigurasiController::class, 'editKonfigurasiGaji'])->name('konfigurasi.edit_gaji');
        Route::put('/gaji/{id}', [KonfigurasiController::class, 'updateKonfigurasiGaji'])->name('konfigurasi.update_gaji');
    });

    // Penggajian Routes
    Route::prefix('penggajian')->group(function () {
        Route::get('/', [PenggajianController::class, 'index']);
        Route::get('/slip/{nik}/{bulan}/{tahun}', [PenggajianController::class, 'cetakSlip']);
    });
});

Route::get('/createrolepermission', function(){
   try {
       $role = Role::firstOrCreate(['name' => 'admin departemen']);
       // Permission::firstOrCreate(['name' => 'view-karyawan']);
       // Permission::firstOrCreate(['name' => 'view-departemen']);
       echo "Sukses: Role dan permission berhasil dibuat.";
   } catch (\Exception $e) {
       echo "Error: " . $e->getMessage();
   }
});

Route::get('/give-user-role', function(){
   try {
       $user = User::findOrFail(1); // Pastikan ID user benar
       $user->assignRole('administrator'); // Berikan role administrator
       echo "Sukses: Role 'administrator' berhasil diberikan ke user.";
   } catch (\Exception $e) {
       echo "Error: " . $e->getMessage();
   }
});

Route::get('/give-role-permission', function (){
   try {
       $role = Role::findOrFail(1);
       $role->givePermissionTo('view-departemen');
       echo "Sukses: Role 'administrator' berhasil diberikan ke user.";
   } catch (\Exception $e) {
       echo "Error: ";
   }
});