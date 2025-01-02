@extends('layouts.presensi')
@section('header')
<!----- App Header ----->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Cetak Slip Gaji</div>
    <div class="right"></div>
</div>

<style>
    /* Styling form untuk tampilan mobile */
    .form-container {
        margin-top: 70px;
        text-align: center;
    }

    .form-container .form-group {
        margin-bottom: 20px;
        text-align: center;
    }

    .form-container .form-control {
        font-size: 20px;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        width: 90%;
        max-width: 300px;
        margin: 0 auto;
        text-align: center;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 3px;
    }

    .form-container .form-control:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
    }

    .form-container .btn {
        margin-top: 15px;
        width: 90%;
        max-width: 300px;
    }

    .form-container label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        text-align: center;
        font-size: 14px;
    }
</style>
<!----- * App Header ----->
@endsection

@section('content')
<div class="form-container">
    <form method="GET" action="{{ url('/penggajian/slip') }}">
        <!-- Pilihan Bulan -->
        <div class="form-group">
            <label for="bulan">Pilih Bulan</label>
            <select name="bulan" id="bulan" class="form-control">
                <option value="" selected>-----</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option {{ Request('bulan') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $namabulan[$i] }}</option>
                @endfor
            </select>
        </div>

        <!-- Pilihan Tahun -->
        <div class="form-group">
            <label for="tahun">Pilih Tahun</label>
            <select name="tahun" id="tahun" class="form-control">
                <option value="" selected>-----</option>
                @php
                    $tahun_awal = 2022;
                    $tahun_sekarang = date("Y");
                    for ($t = $tahun_awal; $t <= $tahun_sekarang; $t++) {
                        echo "<option value='$t' " . (Request('tahun') == $t ? 'selected' : '') . ">$t</option>";
                    }
                @endphp
            </select>
        </div>

        <!-- Tombol Cetak -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Cetak Slip Gaji</button>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $(function () {
        // Validasi sebelum mengirimkan form
        $('form').on('submit', function (e) {
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();

            if (!bulan || !tahun) {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Bulan dan Tahun harus dipilih!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
