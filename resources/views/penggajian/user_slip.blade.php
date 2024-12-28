<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            width: 90%;
            max-width: 400px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #000;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 60px;
            height: auto;
            margin-bottom: 10px;
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
            width: 100%;
            height: 2px;
            background-color: #000;
            margin: 15px 0;
        }

        .details .row {
            display: flex;
            margin-bottom: 10px;
        }

        .details .label {
            width: 120px;
            font-weight: bold;
        }

        .details .value {
            flex: 1;
            text-align: left;
        }

        table.summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        table.summary th, table.summary td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table.summary th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 12px;
        }

        .footer strong {
            display: block;
            margin-top: 5px;
        }

        .button-container {
            margin: 20px auto;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .button-container button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-download {
            background-color: #28a745;
            color: #fff;
        }

        .btn-back {
            background-color: #007bff;
            color: #fff;
        }

        @media screen and (max-width: 400px) {
            .details .label {
                width: 100px;
            }

            .container {
                padding: 10px;
            }

            .header img {
                width: 50px;
            }

            table.summary th, table.summary td {
                font-size: 10px;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ request()->has('download') ? public_path('assets/img/logo_perusahaan.png') : asset('assets/img/logo_perusahaan.png') }}" alt="Logo Perusahaan" style="width: 60px;">
            <h2>MAJU BERKAH TEKNOLOGI</h2>
            <p>Ring-road Utara No 19, Jakarta</p>
            <div class="divider"></div>
            <h3>SLIP GAJI BULAN {{ strtoupper(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F')) }} {{ $tahun }}</h3>
        </div>

        <!-- Details Section -->
        <div class="details">
            <div class="row">
                <span class="label">NIK</span>
                <span class="value">: {{ $karyawan->nik }}</span>
            </div>
            <div class="row">
                <span class="label">Nama</span>
                <span class="value">: {{ $karyawan->nama_lengkap }}</span>
            </div>
            <div class="row">
                <span class="label">Jabatan</span>
                <span class="value">: {{ $karyawan->jabatan }}</span>
            </div>
            <div class="row">
                <span class="label">Departemen</span>
                <span class="value">: {{ $karyawan->departemen }}</span>
            </div>
        </div>

        <!-- Summary Section -->
        <table class="summary">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
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

    <!-- Button Container -->
    @if (!request()->has('download'))
        <div class="button-container">
            <form action="{{ route('penggajian.slip') }}" method="GET" target="_blank">
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
    <input type="hidden" name="download" value="pdf">
    <button type="submit" class="btn-download">Download PDF</button>
</form>

            <button class="btn-back" onclick="window.history.back()">Kembali</button>
        </div>
    @endif
</body>
</html>
