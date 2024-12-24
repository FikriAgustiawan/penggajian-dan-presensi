<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 700px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .divider {
            border-bottom: 2px solid #000;
            margin: 15px 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .details .row {
    display: flex;
    align-items: center;
    margin-bottom: 8px; /* Spasi antar baris */
}

.details .label {
    width: 120px; /* Atur lebar yang sama untuk label */
    text-align: left; /* Pastikan teks rata kiri */
}

.details .value {
    flex: 1; /* Biarkan nilai menyesuaikan lebar konten */
    text-align: left; /* Teks rata kiri */
}

        .details span {
            width: 48%;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .summary th, .summary td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .summary th {
            background-color: #f0f0f0;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 12px;
        }
        .footer strong {
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header" style="text-align: center; margin-bottom: 8px;">
    <div style="display: flex; justify-content: center; align-items: center;">
        <!-- Logo di sisi kiri -->
        <img src="{{ asset('assets/img/logo_perusahaan.png') }}" alt="Logo Perusahaan" style="width: 100px; height: auto; margin-right: 15px;">
        <!-- Informasi teks di sisi kanan -->
    <div>
        <h2 style="margin: 0; font-size: 20px;">MAJU BERKAH TEKNOLOGI</h2>
        <p style="margin: 2px 0; font-size: 14px;">Ring-road Utara No 19, Jakarta</p>
    </div>
</div>

    <div class="divider" style="width: 100%; height: 2px; background-color: #000; margin: 10px 0;"></div>
    <h3 style="margin: 0;">SLIP GAJI BULAN {{ strtoupper(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F')) }} {{ $tahun }}</h3>
</div>


        <!-- Details Section -->
       <div class="details">
    <div class="row">
        <span class="label"><strong>NIK</strong></span>
        <span class="value">: {{ $karyawan->nik }}</span>
    </div>
    <div class="row">
        <span class="label"><strong>Nama</strong></span>
        <span class="value">: {{ $karyawan->nama_lengkap }}</span>
    </div>
    <div class="row">
        <span class="label"><strong>Jabatan</strong></span>
        <span class="value">: {{ $karyawan->jabatan }}</span>
    </div>
    <div class="row">
        <span class="label"><strong>Departemen</strong></span>
        <span class="value">: {{ $karyawan->departemen }}</span>
    </div>
</div>



        <!-- Summary Section -->
        <table class="summary">
           <thead>
    <tr>
        <th style="text-align: center; vertical-align: middle;">Keterangan</th>
        <th style="text-align: center; vertical-align: middle;">Jumlah</th>
    </tr>
</thead>

            <tbody>
                <tr>
                    <td>Gaji Pokok Harian x Total Presensi</td>
                    <td>Rp{{ number_format($gaji_harian, 0, ',', '.') }} x {{ $jumlah_hadir }}</td>
                </tr>
                <tr>
                    <td><strong>Total Penghasilan</strong></td>
                    <td><strong>Rp{{ number_format($jumlah_hadir * $gaji_harian, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Potongan Keterlambatan</td>
                    <td>Rp{{ number_format($total_denda, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Bersih yang Diterima</strong></td>
                    <td><strong>Rp{{ number_format($total_gaji, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <p>Tangerang, {{ date('d F Y') }}</p>
            <strong>Fikri</strong>
            <span>(Manajer HR)</span>
        </div>
    </div>
</body>
</html>
